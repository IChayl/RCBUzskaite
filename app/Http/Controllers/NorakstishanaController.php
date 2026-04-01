<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Norakstishana;
use App\Models\Inventar;
use App\Models\InventaraKustiba;
use App\Models\KustibasVeidi;
use App\Models\Lietotajs;
use App\Http\Controllers\Concerns\HandlesSafeDelete;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

// Kontrolieris norakstīšanas ierakstu pārvaldībai.
class NorakstishanaController extends Controller
{
    use HandlesSafeDelete;
    /**
     * Parāda norakstīšanas sarakstu ar meklēšanu, kārtošanu un lapošanu.
     */
    public function showAll(Request $request)
    {
        $user = auth()->user();
        $pendingNorakstishanaCount = 0;
        $pendingNorakstishanaRequests = collect();

        // Meklēšanas teksta un kolonnas iestatījumi
        $q = trim($request->input('q', ''));
        $column = $request->input('column', 'all');
        $dateFrom = $request->input('nor_datums_no');
        $dateTo = $request->input('nor_datums_lidz');

        // Kārtošanas parametri
        $sort = $request->input('sort', 'norakstishana_id');
        $direction = strtolower($request->input('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        // Atļautās kolonnas kārtošanai
        $allowedSort = ['norakstishana_id', 'inventara_numurs', 'inventars', 'norDatums', 'pieteikshanas_dat', 'apstiprinashanas_dat', 'akceptets', 'iemesls', 'talaka_riciba'];
        if (!in_array($sort, $allowedSort, true)) {
            $sort = 'norakstishana_id';
        }

        // Atļautās kolonnas meklēšanai
        $allowedColumns = ['all', 'nosaukums', 'inventara_numurs'];
        if (!in_array($column, $allowedColumns, true)) {
            $column = 'all';
        }

        $query = Norakstishana::query()
            ->with(['inventars', 'pieteicejs']);

        if (! $user->admina_tiesibas) {
            $query->where('pieteica_lietotajs_id', $user->lietotajs_id);
        }

        if ($user->admina_tiesibas) {
            $pendingNorakstishanaCount = Norakstishana::query()
                ->where('akceptets', false)
                ->count();

            $pendingNorakstishanaRequests = Norakstishana::query()
                ->with(['inventars', 'pieteicejs'])
                ->where('akceptets', false)
                ->orderByDesc('pieteikshanas_dat')
                ->orderByDesc('norakstishana_id')
                ->limit(5)
                ->get();
        }

        // Meklēšana kolonnā vai visās kolonnās
        if ($q !== '') {
            if ($column === 'all') {
                $query->where(function ($query) use ($q) {
                    $query->whereHas('inventars', function ($q2) use ($q) {
                            $q2->where('nosaukums', 'like', "%{$q}%")
                                ->orWhere('inventara_numurs', 'like', "%{$q}%");
                        });
                });
            } elseif ($column === 'nosaukums') {
                $query->whereHas('inventars', function ($q2) use ($q) {
                    $q2->where('nosaukums', 'like', "%{$q}%");
                });
            } elseif ($column === 'inventara_numurs') {
                $query->whereHas('inventars', function ($q2) use ($q) {
                    $q2->where('inventara_numurs', 'like', "%{$q}%");
                });
            }
        }

        if (!empty($dateFrom)) {
            $query->whereDate('norDatums', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->whereDate('norDatums', '<=', $dateTo);
        }

        // Kārtošana
        if ($sort === 'inventara_numurs') {
            $query->leftJoin('inventars', 'Norakstishana.inventara_id', '=', 'inventars.inventars_id')
                ->orderBy('inventars.inventara_numurs', $direction)
                ->select('Norakstishana.*');
        } elseif ($sort === 'inventars') {
            $query->leftJoin('inventars', 'Norakstishana.inventara_id', '=', 'inventars.inventars_id')
                ->orderBy('inventars.nosaukums', $direction)
                ->select('Norakstishana.*');
        } else {
            $query->orderBy($sort, $direction);
        }

        $norakstishanas = $query->paginate(7)->withQueryString();

        return view('norakstishana', compact(
            'norakstishanas',
            'sort',
            'direction',
            'q',
            'column',
            'dateFrom',
            'dateTo',
            'pendingNorakstishanaCount',
            'pendingNorakstishanaRequests'
        ));
    }

    /**
     * Ielādē formas datus jaunas norakstīšanas izveidei.
     */
    public function create()
    {
        $user = auth()->user();

        $inventari = $this->buildAvailableInventarsQuery($user)
            ->orderBy('nosaukums', 'asc')
            ->get();

        return view('createNorakstishana', ['inventari' => $inventari]);
    }

    /**
     * Validē ievadi un saglabā jaunu norakstīšanas ierakstu.
     */
    public function store(Request $req)
    {
        $user = auth()->user();

        $data = $req->validate([
            'inventara_id' => 'required|integer|exists:inventars,inventars_id',
            'norDatums' => 'required|date',
            'iemesls' => 'required|string|max:30',
            'talaka_riciba' => 'required|string|max:50',
        ]);

        $inventars = Inventar::findOrFail($data['inventara_id']);
        if (! $user->admina_tiesibas && (int) $inventars->atbildigais_id !== (int) $user->lietotajs_id) {
            abort(403, 'Jums nav tiesību pieteikt norakstīšanu šim inventāram.');
        }

        $this->ensureInventarIsNotAlreadyWrittenOff((int) $data['inventara_id']);

        $norakstishana = new Norakstishana();
        $norakstishana->inventara_id = $data['inventara_id'];
        $norakstishana->norDatums = $data['norDatums'];
        $norakstishana->pieteikshanas_dat = Carbon::today();
        $norakstishana->pieteica_lietotajs_id = $user->lietotajs_id;
        $norakstishana->akceptets = $user->admina_tiesibas;
        $norakstishana->apstiprinashanas_dat = $this->resolveApprovalDateForStore($user->admina_tiesibas);
        $norakstishana->iemesls = $data['iemesls'];
        $norakstishana->talaka_riciba = $data['talaka_riciba'];
        $norakstishana->save();

        if ($norakstishana->akceptets) {
            $this->createKustibaForAcceptedNorakstishana($norakstishana);
        }

        return redirect()->to('/norakstishana')->with('success', $user->admina_tiesibas ? 'Ieraksts pievienots un akceptēts.' : 'Norakstīšanas pieteikums iesniegts.');
    }

    /**
     * Parāda viena norakstīšanas detalizēto skatu.
     */
    public function details($id)
    {
        $norakstishana = Norakstishana::with(['inventars', 'pieteicejs'])->findOrFail($id);

        if (! auth()->user()->admina_tiesibas && (int) $norakstishana->pieteica_lietotajs_id !== (int) auth()->user()->lietotajs_id) {
            abort(403, 'Jums nav piekļuves šim pieteikumam.');
        }

        return view('detailsNorakstishana', ['norakstishana' => $norakstishana]);
    }

    /**
     * Atver norakstīšanas rediģēšanas formu (tikai administratoram).
     */
    public function edit($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $norakstishana = Norakstishana::findOrFail($id);
        $inventari = $this->buildAvailableInventarsQuery(auth()->user(), $norakstishana)
            ->orderBy('nosaukums', 'asc')
            ->get();
        return view('editNorakstishana', ['norakstishana' => $norakstishana, 'inventari' => $inventari]);
    }

    /**
     * Saglabā norakstīšanas izmaiņas datubāzē.
     */
    public function update(Request $req, $id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $data = $req->validate([
            'inventara_id' => 'required|integer|exists:inventars,inventars_id',
            'norDatums' => 'required|date',
            'iemesls' => 'required|string|max:30',
            'talaka_riciba' => 'required|string|max:50',
        ]);

        $this->ensureInventarIsNotAlreadyWrittenOff((int) $data['inventara_id'], (int) $id);

        DB::table('Norakstishana')->where('norakstishana_id',$id)->update([
            'inventara_id' => $data['inventara_id'],
            'norDatums' => $data['norDatums'],
            'iemesls' => $data['iemesls'],
            'talaka_riciba' => $data['talaka_riciba'],
        ]);

        return redirect()->to('/norakstishana')->with('success','Ieraksts atjaunināts');
    }

    /**
     * Akceptē norakstīšanas pieteikumu (tikai administratoram).
     */
    public function accept($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }

        $norakstishana = Norakstishana::findOrFail($id);
        $this->ensureInventarIsNotAlreadyWrittenOff((int) $norakstishana->inventara_id, (int) $norakstishana->norakstishana_id);
        $norakstishana->akceptets = true;
        $norakstishana->apstiprinashanas_dat = Carbon::today();
        $norakstishana->save();
        $this->createKustibaForAcceptedNorakstishana($norakstishana);

        return redirect()->to('/norakstishana')->with('success', 'Norakstīšanas pieteikums akceptēts.');
    }

    /**
     * Atceļ neakceptētu norakstīšanas pieteikumu (tikai administratoram).
     */
    public function cancel($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }

        $norakstishana = Norakstishana::findOrFail($id);

        if ($norakstishana->akceptets) {
            return redirect()->to('/norakstishana')->with('error', 'Akceptētu pieteikumu nevar atcelt.');
        }

        $norakstishana->delete();

        return redirect()->to('/norakstishana')->with('success', 'Norakstīšanas pieteikums atcelts.');
    }

    /**
     * Dzēš norakstīšanas ierakstu (tikai administratoram).
     */
    public function delete($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $norakstishana = Norakstishana::findOrFail($id);
        $this->deleteWithForeignKeyChecksDisabled('Norakstishana', 'norakstishana_id', $id);

        if ($norakstishana->akceptets) {
            DB::table('Norakstishana')
                ->where('inventara_id', $norakstishana->inventara_id)
                ->update([
                    'akceptets' => false,
                    'apstiprinashanas_dat' => null,
                ]);
        }

        return redirect('/norakstishana')->with('success', $this->buildDeleteMessage('Norakstīšanas', []));
    }

    /**
     * Nodrošina saderību ar vidi, kur apstiprināšanas datums kļūdaini definēts kā NOT NULL.
     */
    private function resolveApprovalDateForStore(bool $isAdmin): ?Carbon
    {
        if ($isAdmin) {
            return Carbon::today();
        }

        try {
            $columnMetadata = DB::selectOne(
                'SELECT IS_NULLABLE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ? LIMIT 1',
                ['Norakstishana', 'apstiprinashanas_dat']
            );
        } catch (\Throwable $exception) {
            return Carbon::today();
        }

        if ($columnMetadata && strtoupper((string) $columnMetadata->IS_NULLABLE) === 'NO') {
            return Carbon::today();
        }

        return null;
    }

    private function buildAvailableInventarsQuery(Lietotajs $user, ?Norakstishana $currentNorakstishana = null)
    {
        return Inventar::query()
            ->when(! $user->admina_tiesibas, function ($query) use ($user) {
                $query->where('atbildigais_id', $user->lietotajs_id);
            })
            ->where(function ($query) use ($currentNorakstishana) {
                $query->withoutAcceptedNorakstishana();

                if ($currentNorakstishana) {
                    $query->orWhere('inventars_id', $currentNorakstishana->inventara_id);
                }
            });
    }

    private function ensureInventarIsNotAlreadyWrittenOff(int $inventaraId, ?int $ignoreNorakstishanaId = null): void
    {
        $alreadyWrittenOff = Norakstishana::query()
            ->where('inventara_id', $inventaraId)
            ->where('akceptets', true)
            ->when($ignoreNorakstishanaId !== null, function ($query) use ($ignoreNorakstishanaId) {
                $query->where('norakstishana_id', '!=', $ignoreNorakstishanaId);
            })
            ->exists();

        if ($alreadyWrittenOff) {
            throw ValidationException::withMessages([
                'inventara_id' => 'Šis inventārs jau ir norakstīts.',
            ]);
        }
    }

    private function createKustibaForAcceptedNorakstishana(Norakstishana $norakstishana): void
    {
        $norakstishana = $norakstishana->fresh(['inventars']);

        if (! $norakstishana || ! $norakstishana->akceptets) {
            return;
        }

        $norakstisanaVeidsId = KustibasVeidi::query()
            ->whereRaw('LOWER(nosaukums) like ?', ['%norakst%'])
            ->value('kustibas_veids_id');

        if (empty($norakstisanaVeidsId)) {
            return;
        }

        $documentRef = '[NORAKSTISHANA:' . $norakstishana->norakstishana_id . ']';

        $alreadyExists = InventaraKustiba::query()
            ->where('piezimes', 'like', '%' . $documentRef . '%')
            ->exists();

        if ($alreadyExists) {
            return;
        }

        $inventars = $norakstishana->inventars;

        if (! $inventars || empty($inventars->atbildigais_id)) {
            return;
        }

        InventaraKustiba::query()->create([
            'datums' => optional($norakstishana->norDatums)->toDateString() ?? Carbon::today()->toDateString(),
            'inventars_id' => $norakstishana->inventara_id,
            'atbildigais_lietotajs_id' => (int) $inventars->atbildigais_id,
            'kustibas_veids_id' => (int) $norakstisanaVeidsId,
            'veca_telpa_id' => $inventars->telpas_id,
            'jauna_telpa_id' => null,
            'piezimes' => 'Automātiski izveidots no norakstīšanas pieteikuma. ' . $documentRef,
            
        ]);
    }
}
