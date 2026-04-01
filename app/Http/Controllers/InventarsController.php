<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventar;
use App\Models\Telpa;
use App\Models\KategorijaModel;
use App\Models\Lietotajs;
use App\Http\Controllers\Concerns\HandlesSafeDelete;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

// Kontrolieris inventāra ierakstu sarakstam, izveidei, labošanai un dzēšanai.
class InventarsController extends Controller
{
    use HandlesSafeDelete;
    /**
     * Parāda inventāra sarakstu ar meklēšanu, kārtošanu un lapošanu.
     */
    public function showAllInventars(Request $request)
    {
        $user = auth()->user();
        $inventoryStatus = $request->input('inventory_status', $user->admina_tiesibas ? 'in_use' : 'all');
        $inventoryScope = $request->input('inventory_scope', $user->admina_tiesibas ? 'all' : 'responsible');

        // Meklēšanas teksta un kolonnas iestatījumi
        $q = trim($request->input('q', ''));
        $column = $request->input('column', 'all');

        // Kārtošanas parametri
        $sort = $request->input('sort', 'inventars_id');
        $direction = strtolower($request->input('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        // Atļautās kolonnas kārtošanai
        $allowedSort = ['inventars_id', 'nosaukums', 'kategorija', 'telpa', 'atbildigais', 'inventara_numurs', 'iegades_datums'];
        if (!in_array($sort, $allowedSort, true)) {
            $sort = 'inventars_id';
        }

        // Atļautās kolonnas meklēšanai
        $allowedColumns = ['all', 'nosaukums', 'inventara_numurs'];
        if (!in_array($column, $allowedColumns, true)) {
            $column = 'all';
        }

        $allowedInventoryStatuses = ['in_use', 'in_repair', 'written_off', 'all'];
        if (! in_array($inventoryStatus, $allowedInventoryStatuses, true)) {
            $inventoryStatus = 'in_use';
        }

        $allowedInventoryScopes = ['responsible', 'all'];
        if (! in_array($inventoryScope, $allowedInventoryScopes, true)) {
            $inventoryScope = $user->admina_tiesibas ? 'all' : 'responsible';
        }

        if ($user->admina_tiesibas) {
            $inventoryScope = 'all';
        }

        if (! $user->admina_tiesibas && $inventoryStatus === 'written_off') {
            $inventoryScope = 'all';
        }

        $query = Inventar::query()
            ->with(['kategorija', 'telpa', 'atbildigais']);

        $filterKategorija = $request->input('filter_kategorija');
        $filterTelpa = $request->input('filter_telpa');
        $filterAtbildigais = $request->input('filter_atbildigais');
        $dateFrom = $request->input('iegades_datums_no');
        $dateTo = $request->input('iegades_datums_lidz');

        if ($inventoryScope === 'responsible' && ! $user->admina_tiesibas && $inventoryStatus === 'all') {
            $query->where(function ($responsibleQuery) use ($user) {
                $responsibleQuery->where('atbildigais_id', $user->lietotajs_id)
                    ->orWhereHas('norakstishanas', function ($subQuery) {
                        $subQuery->where('akceptets', true);
                    });
            });
        } elseif ($inventoryScope === 'responsible') {
            $query->where('atbildigais_id', $user->lietotajs_id);
        }

        if ($inventoryStatus === 'written_off') {
            $query->onlyAcceptedNorakstishana();
        } elseif ($inventoryStatus === 'in_repair') {
            $query->withoutAcceptedNorakstishana();
            $this->applyRepairStatusFilter($query);
        } elseif ($inventoryStatus === 'in_use') {
            $query->withoutAcceptedNorakstishana();
            $this->applyNotInRepairStatusFilter($query);
        }

        // Meklēšana kolonnā vai visās kolonnās
        if ($q !== '') {
            if ($column === 'all') {
                $query->where(function ($query) use ($q) {
                    $query->where('nosaukums', 'like', "%{$q}%")
                        ->orWhere('inventara_numurs', 'like', "%{$q}%");
                });
            } elseif ($column === 'nosaukums') {
                $query->where('nosaukums', 'like', "%{$q}%");
            } elseif ($column === 'inventara_numurs') {
                $query->where('inventara_numurs', 'like', "%{$q}%");
            }
        }

        if (!empty($filterKategorija)) {
            $query->where('kategorija_id', (int) $filterKategorija);
        }

        if (!empty($filterTelpa)) {
            $query->where('telpas_id', (int) $filterTelpa);
        }

        if (!empty($filterAtbildigais)) {
            $query->where('atbildigais_id', (int) $filterAtbildigais);
        }

        if (!empty($dateFrom)) {
            $query->whereDate('iegades_datums', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->whereDate('iegades_datums', '<=', $dateTo);
        }

        // Kārtošana pēc saistīto modeļu lauka
        if ($sort === 'kategorija') {
            $query->leftJoin('kategorija', 'inventars.kategorija_id', '=', 'kategorija.kategorija_id')
                ->orderBy('kategorija.nosaukums', $direction)
                ->select('inventars.*');
        } elseif ($sort === 'telpa') {
            $query->leftJoin('telpa', 'inventars.telpas_id', '=', 'telpa.telpas_id')
                ->orderBy('telpa.nosaukums', $direction)
                ->select('inventars.*');
        } elseif ($sort === 'atbildigais') {
            $query->leftJoin('lietotajs', 'inventars.atbildigais_id', '=', 'lietotajs.lietotajs_id')
                ->orderBy('lietotajs.lietotajvards', $direction)
                ->select('inventars.*');
        } else {
            $query->orderBy($sort, $direction);
        }

        // Paginācija ar querystring, lai saglabātu meklēšanas un kārtošanas parametrus
        $inventari = $query->paginate(7)->withQueryString();

        $kategorijas = KategorijaModel::orderBy('nosaukums')->get();
        $telpas = Telpa::orderBy('nosaukums')->get();
        $lietotaji = Lietotajs::orderBy('lietotajvards')->get();

        // Nosūtām datus uz skatu
        return view('inventars', compact(
            'inventari',
            'sort',
            'direction',
            'q',
            'column',
            'kategorijas',
            'telpas',
            'lietotaji',
            'filterKategorija',
            'filterTelpa',
            'filterAtbildigais',
            'dateFrom',
            'dateTo',
            'inventoryStatus',
            'inventoryScope'
        ));
    }

    private function applyRepairStatusFilter($query): void
    {
        $query->whereExists(function ($subQuery) {
            $subQuery->selectRaw('1')
                ->from('inventara_kustiba as ik')
                ->join('kustibas_veidi as kv', 'kv.kustibas_veids_id', '=', 'ik.kustibas_veids_id')
                ->whereColumn('ik.inventars_id', 'inventars.inventars_id')
                ->whereRaw('LOWER(kv.nosaukums) LIKE ?', ['%remont%'])
                ->whereRaw('ik.kustiba_id = (SELECT ik2.kustiba_id FROM inventara_kustiba ik2 WHERE ik2.inventars_id = inventars.inventars_id ORDER BY ik2.datums DESC, ik2.kustiba_id DESC LIMIT 1)');
        });
    }

    private function applyNotInRepairStatusFilter($query): void
    {
        $query->whereNotExists(function ($subQuery) {
            $subQuery->selectRaw('1')
                ->from('inventara_kustiba as ik')
                ->join('kustibas_veidi as kv', 'kv.kustibas_veids_id', '=', 'ik.kustibas_veids_id')
                ->whereColumn('ik.inventars_id', 'inventars.inventars_id')
                ->whereRaw('LOWER(kv.nosaukums) LIKE ?', ['%remont%'])
                ->whereRaw('ik.kustiba_id = (SELECT ik2.kustiba_id FROM inventara_kustiba ik2 WHERE ik2.inventars_id = inventars.inventars_id ORDER BY ik2.datums DESC, ik2.kustiba_id DESC LIMIT 1)');
        });
    }

    /**
     * Ielādē formas datus jauna inventāra izveidei.
     */
    public function createInventar()
    {
        $telpas = Telpa::orderBy('telpas_id','asc')->get();
        $kategorijas = KategorijaModel::orderBy('kategorija_id','asc')->get();
        $lietotaji = Lietotajs::orderBy('lietotajs_id','asc')->get();
        return view('createInventar', ['telpas' => $telpas, 'kategorijas' => $kategorijas, 'lietotaji' => $lietotaji]);
    }

    /**
     * Validē ievadi un saglabā jaunu inventāra ierakstu.
     */
    public function InventarSubmit(Request $req)
    {
        $data = $req->validate([
            'nosaukums' => 'required|string|max:30',
            'kategorija_id' => 'required|integer|exists:kategorija,kategorija_id',
            'telpas_id' => 'required|integer|exists:telpa,telpas_id',
            'atbildigais_id' => 'nullable|integer|exists:lietotajs,lietotajs_id',
            'inventara_numurs' => ['nullable', 'string', 'max:50', Rule::unique('inventars', 'inventara_numurs')],
            'iegades_datums' => 'nullable|date',
        ]);

        // Izveido ierakstu
        $i = new Inventar();
        $i->nosaukums = $data['nosaukums'];
        $i->kategorija_id = $data['kategorija_id'];
        $i->telpas_id = $data['telpas_id'];
        $i->atbildigais_id = $data['atbildigais_id'] ?? null;
        $i->inventara_numurs = $data['inventara_numurs'] ?? null;
        $i->iegades_datums = $data['iegades_datums'] ?? null;
        $i->save();

        return redirect()->to('/inventars')->with('success','Ieraksts pievienots');
    }

    /**
     * Parāda viena inventāra detalizēto skatu.
     */
    public function InventarDetails($id)
    {
        $i = Inventar::with(['kategorija', 'telpa', 'atbildigais'])->findOrFail($id);

        if (! auth()->user()->admina_tiesibas && (int) $i->atbildigais_id !== (int) auth()->user()->lietotajs_id) {
            abort(403, 'Jums nav piekļuves šim inventāram.');
        }

        return view('detailsInventar', ['inventar' => $i]);
    }

    /**
     * Atver inventāra rediģēšanas formu (tikai administratoram).
     */
    public function InventarEdit($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $i = Inventar::find($id);
        $telpas = Telpa::orderBy('telpas_id','asc')->get();
        $kategorijas = KategorijaModel::orderBy('kategorija_id','asc')->get();
        $lietotaji = Lietotajs::orderBy('lietotajs_id','asc')->get();
        return view('editInventar', ['inventar' => $i, 'telpas' => $telpas, 'kategorijas' => $kategorijas, 'lietotaji' => $lietotaji]);
    }

    /**
     * Saglabā inventāra izmaiņas datubāzē.
     */
    public function editSubmit(Request $req, $id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $data = $req->validate([
            'nosaukums' => 'required|string|max:30',
            'kategorija_id' => 'required|integer|exists:kategorija,kategorija_id',
            'telpas_id' => 'required|integer|exists:telpa,telpas_id',
            'atbildigais_id' => 'nullable|integer|exists:lietotajs,lietotajs_id',
            'inventara_numurs' => ['nullable', 'string', 'max:50', Rule::unique('inventars', 'inventara_numurs')->ignore($id, 'inventars_id')],
            'iegades_datums' => 'nullable|date',
        ]);

        DB::table('inventars')->where('inventars_id',$id)->update([
            'nosaukums' => $data['nosaukums'],
            'kategorija_id' => $data['kategorija_id'],
            'telpas_id' => $data['telpas_id'],
            'atbildigais_id' => $data['atbildigais_id'] ?? null,
            'inventara_numurs' => $data['inventara_numurs'] ?? null,
            'iegades_datums' => $data['iegades_datums'] ?? null,
        ]);

        return redirect()->to('/inventars')->with('success','Ieraksts atjaunināts');
    }

    /**
     * Dzēš inventāra ierakstu (tikai administratoram).
     */
    public function InventarDelete($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $usedIn = $this->detectReferenceUsage($id, [
            ['table' => 'inventara_kustiba', 'column' => 'inventars_id', 'label' => 'inventara_kustiba.inventars_id'],
            ['table' => 'Norakstishana', 'column' => 'inventara_id', 'label' => 'Norakstishana.inventara_id'],
        ]);

        $this->deleteWithForeignKeyChecksDisabled('inventars', 'inventars_id', $id);

        return redirect('/inventars')->with('success', $this->buildDeleteMessage('Inventāra', $usedIn));
    }
}
