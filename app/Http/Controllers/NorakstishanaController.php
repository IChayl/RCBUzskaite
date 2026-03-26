<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Norakstishana;
use App\Models\Inventar;
use App\Models\Lietotajs;
use App\Http\Controllers\Concerns\HandlesSafeDelete;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

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
        $allowedSort = ['norakstishana_id', 'inventars', 'norDatums', 'pieteikshanas_dat', 'apstiprinashanas_dat', 'akceptets', 'iemesls', 'talaka_riciba'];
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
        } else {
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
        if ($sort === 'inventars') {
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

        $inventari = Inventar::query()
            ->when(! $user->admina_tiesibas, function ($query) use ($user) {
                $query->where('atbildigais_id', $user->lietotajs_id);
            })
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

        $norakstishana = new Norakstishana();
        $norakstishana->inventara_id = $data['inventara_id'];
        $norakstishana->norDatums = $data['norDatums'];
        $norakstishana->pieteikshanas_dat = Carbon::today();
        $norakstishana->pieteica_lietotajs_id = $user->lietotajs_id;
        $norakstishana->akceptets = $user->admina_tiesibas;
        $norakstishana->apstiprinashanas_dat = $user->admina_tiesibas ? Carbon::today() : null;
        $norakstishana->iemesls = $data['iemesls'];
        $norakstishana->talaka_riciba = $data['talaka_riciba'];
        $norakstishana->save();

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
        $inventari = Inventar::orderBy('nosaukums','asc')->get();
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
        $norakstishana->akceptets = true;
        $norakstishana->apstiprinashanas_dat = Carbon::today();
        $norakstishana->save();

        return redirect()->to('/norakstishana')->with('success', 'Norakstīšanas pieteikums akceptēts.');
    }

    /**
     * Dzēš norakstīšanas ierakstu (tikai administratoram).
     */
    public function delete($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $this->deleteWithForeignKeyChecksDisabled('Norakstishana', 'norakstishana_id', $id);

        return redirect('/norakstishana')->with('success', $this->buildDeleteMessage('Norakstīšanas', []));
    }
}
