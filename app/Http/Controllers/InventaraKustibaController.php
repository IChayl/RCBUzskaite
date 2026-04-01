<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventaraKustiba;
use App\Models\Inventar;
use App\Models\KustibasVeidi;
use App\Models\Lietotajs;
use App\Models\Telpa;
use App\Http\Controllers\Concerns\HandlesSafeDelete;
use Illuminate\Support\Facades\DB;

// Kontrolieris inventāra kustību pārvaldībai.
class InventaraKustibaController extends Controller
{
    use HandlesSafeDelete;
    /**
     * Parāda kustību sarakstu ar filtrēšanu, kārtošanu un lapošanu.
     */
    public function showAllKustiba(Request $request)

    {
        $user = auth()->user();
    // Meklēšanas teksts un izvēlētā kolonna (vai "all" līdz meklēšanai visur)
        $q = trim($request->input('q', ''));
        $column = $request->input('column', 'all');
        $filterVeids = $request->input('filter_veids');
        $filterAtbildigais = $request->input('filter_atbildigais');
        $dateFrom = $request->input('datums_no');
        $dateTo = $request->input('datums_lidz');

        // Kārtošanas iestatījumi: kolonna un virziens (asc/desc)
        $sort = $request->input('sort', 'kustiba_id');
        $direction = strtolower($request->input('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        // Drošība: atļautās kolonnas, pēc kurām var kārtot
        $allowedSort = ['kustiba_id', 'datums', 'inventara_numurs', 'inventars', 'kustibas_veids', 'lietotajs', 'veca_telpa', 'jauna_telpa'];
        if (!in_array($sort, $allowedSort, true)) {
            $sort = 'kustiba_id';
        }

        // Atļautās kolonnas meklēšanai.
        $allowedColumns = ['all', 'inventars', 'inventara_numurs'];
        if (!in_array($column, $allowedColumns, true)) {
            $column = 'all';
        }

        // Ielādējam saistītos modeļus, lai samazinātu papildus SQL pieprasījumus skatā.
        $query = InventaraKustiba::query()->with(['inventars', 'lietotajs', 'kustibasVeids']);

        // Ja lietotājs nav admins, rādam tikai viņa kustības.
        if (! $user->admina_tiesibas) {
            $query->where('atbildigais_lietotajs_id', $user->lietotajs_id);
        }   

        // Meklēšanas loģika pa vienu vai visām kolonnām.
        if ($q !== '') {
            if ($column === 'all') {
                $query->where(function ($query) use ($q) {
                    $query->whereHas('inventars', function ($q2) use ($q) {
                            $q2->where('nosaukums', 'like', "%{$q}%");
                        });
                });
            } elseif ($column === 'inventars') {
                $query->whereHas('inventars', function ($q2) use ($q) {
                    $q2->where('nosaukums', 'like', "%{$q}%");
                });
            } elseif ($column === 'inventara_numurs') {
                $query->whereHas('inventars', function ($q2) use ($q) {
                    $q2->where('inventara_numurs', 'like', "%{$q}%");
                });
            }
        }

        if (!empty($filterVeids)) {
            $query->where('kustibas_veids_id', (int) $filterVeids);
        }

        if (!empty($filterAtbildigais)) {
            $query->where('atbildigais_lietotajs_id', (int) $filterAtbildigais);
        }

        if (!empty($dateFrom)) {
            $query->whereDate('datums', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->whereDate('datums', '<=', $dateTo);
        }

        // Kārtošana pēc saistītajiem modeļiem (jāizmanto join, lai var kārtot pēc saistītajām tabulām)
        if ($sort === 'inventars') {
            $query->leftJoin('inventars', 'inventara_kustiba.inventars_id', '=', 'inventars.inventars_id')
                ->orderBy('inventars.nosaukums', $direction)
                ->select('inventara_kustiba.*');
        } elseif ($sort === 'inventara_numurs') {
            $query->leftJoin('inventars', 'inventara_kustiba.inventars_id', '=', 'inventars.inventars_id')
                ->orderBy('inventars.inventara_numurs', $direction)
                ->select('inventara_kustiba.*');
        } elseif ($sort === 'kustibas_veids') {
            $query->leftJoin('kustibas_veidi', 'inventara_kustiba.kustibas_veids_id', '=', 'kustibas_veidi.kustibas_veids_id')
                ->orderBy('kustibas_veidi.nosaukums', $direction)
                ->select('inventara_kustiba.*');
        } elseif ($sort === 'lietotajs') {
            $query->leftJoin('lietotajs', 'inventara_kustiba.atbildigais_lietotajs_id', '=', 'lietotajs.lietotajs_id')
                ->orderBy('lietotajs.lietotajvards', $direction)
                ->select('inventara_kustiba.*');
        } elseif ($sort === 'veca_telpa') {
            $query->leftJoin('telpa as veca', 'inventara_kustiba.veca_telpa_id', '=', 'veca.telpas_id')
                ->orderBy('veca.nosaukums', $direction)
                ->select('inventara_kustiba.*');
        } elseif ($sort === 'jauna_telpa') {
            $query->leftJoin('telpa as jauna', 'inventara_kustiba.jauna_telpa_id', '=', 'jauna.telpas_id')
                ->orderBy('jauna.nosaukums', $direction)
                ->select('inventara_kustiba.*');
        } else {
            $query->orderBy($sort, $direction);
        }

        $kustibas = $query->paginate(7)->withQueryString();

        $kustibasVeidiFiltram = KustibasVeidi::orderBy('nosaukums')->get();
        $lietotajiFiltram = Lietotajs::orderBy('lietotajvards')->get();

        return view('inventara_kustiba', compact(
            'kustibas',
            'sort',
            'direction',
            'q',
            'column',
            'kustibasVeidiFiltram',
            'lietotajiFiltram',
            'filterVeids',
            'filterAtbildigais',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Atver kustības izveides formu ar nepieciešamajiem izvēļņu datiem.
     */
    public function createKustiba()
    {
        $inventari = Inventar::orderBy('inventars_id','asc')->get();
        $kustibasVeidi = KustibasVeidi::orderBy('kustibas_veids_id','asc')->get();
        $lietotaji = Lietotajs::orderBy('lietotajs_id','asc')->get();
        $telpas = Telpa::orderBy('telpas_id','asc')->get();
        return view('createInventaraKustiba', ['inventari' => $inventari, 'kustibasVeidi' => $kustibasVeidi, 'lietotaji' => $lietotaji, 'telpas' => $telpas]);
    }

    /**
     * Validē un saglabā jaunu inventāra kustību.
     */
    public function KustibaSubmit(Request $req)
    {
        $data = $req->validate([
            'datums' => 'required|date',
            'inventars_id' => 'required|integer|exists:inventars,inventars_id',
            'kustibas_veids_id' => 'nullable|integer|exists:kustibas_veidi,kustibas_veids_id',
            'atbildigais_lietotajs_id' => 'required|integer|exists:lietotajs,lietotajs_id',
            'veca_telpa_id' => 'nullable|integer|exists:telpa,telpas_id',
            'jauna_telpa_id' => 'nullable|integer|exists:telpa,telpas_id',
            'piezimes' => 'nullable|string|max:255',
            'dokuments' => 'nullable|string|max:255',
        ]);

        $inventars = Inventar::findOrFail((int) $data['inventars_id']);
        if ($this->isParvietosanaMovement($data['kustibas_veids_id'] ?? null)) {
            $data['veca_telpa_id'] = $inventars->telpas_id;
        }

        $i = new InventaraKustiba();
        $i->datums = $data['datums'];
        $i->inventars_id = $data['inventars_id'];
        $i->kustibas_veids_id = $data['kustibas_veids_id'] ?? null;
        $i->atbildigais_lietotajs_id = $data['atbildigais_lietotajs_id'];
        $i->veca_telpa_id = $data['veca_telpa_id'] ?? null;
        $i->jauna_telpa_id = $data['jauna_telpa_id'] ?? null;
        $i->piezimes = $data['piezimes'] ?? null;
        $i->dokuments = $data['dokuments'] ?? null;
        $i->save();

        return redirect()->to('/inventara_kustiba')->with('success','Ieraksts pievienots');
    }

    /**
     * Parāda vienas kustības detalizētu informāciju.
     */
    public function KustibaDetails($id)
    {
        $i = InventaraKustiba::with(['inventars','lietotajs','kustibasVeids'])->find($id);
        return view('detailsInventaraKustiba', ['kustiba' => $i]);
    }

    /**
     * Atver kustības rediģēšanas formu (tikai administratoram).
     */
    public function KustibaEdit($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $i = InventaraKustiba::find($id);
        $inventari = Inventar::orderBy('inventars_id','asc')->get();
        $kustibasVeidi = KustibasVeidi::orderBy('kustibas_veids_id','asc')->get();
        $lietotaji = Lietotajs::orderBy('lietotajs_id','asc')->get();
        $telpas = Telpa::orderBy('telpas_id','asc')->get();
        return view('editInventaraKustiba', ['kustiba' => $i, 'inventari' => $inventari, 'kustibasVeidi' => $kustibasVeidi, 'lietotaji' => $lietotaji, 'telpas' => $telpas]);
    }

    /**
     * Saglabā kustības ieraksta izmaiņas.
     */
    public function editSubmit(Request $req, $id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $data = $req->validate([
            'datums' => 'required|date',
            'inventars_id' => 'required|integer|exists:inventars,inventars_id',
            'kustibas_veids_id' => 'nullable|integer|exists:kustibas_veidi,kustibas_veids_id',
            'atbildigais_lietotajs_id' => 'required|integer|exists:lietotajs,lietotajs_id',
            'veca_telpa_id' => 'nullable|integer|exists:telpa,telpas_id',
            'jauna_telpa_id' => 'nullable|integer|exists:telpa,telpas_id',
            'piezimes' => 'nullable|string|max:255',
            'dokuments' => 'nullable|string|max:255',
        ]);

        $inventars = Inventar::findOrFail((int) $data['inventars_id']);
        if ($this->isParvietosanaMovement($data['kustibas_veids_id'] ?? null)) {
            $data['veca_telpa_id'] = $inventars->telpas_id;
        }

        DB::table('inventara_kustiba')->where('kustiba_id',$id)->update([
            'datums' => $data['datums'],
            'inventars_id' => $data['inventars_id'],
            'kustibas_veids_id' => $data['kustibas_veids_id'] ?? null,
            'atbildigais_lietotajs_id' => $data['atbildigais_lietotajs_id'],
            'veca_telpa_id' => $data['veca_telpa_id'] ?? null,
            'jauna_telpa_id' => $data['jauna_telpa_id'] ?? null,
            'piezimes' => $data['piezimes'] ?? null,
            'dokuments' => $data['dokuments'] ?? null,
        ]);
        return redirect()->to('/inventara_kustiba')->with('success','Ieraksts atjaunināts');
    }

    /**
     * Dzēš kustības ierakstu (tikai administratoram).
     */
    public function KustibaDelete($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $this->deleteWithForeignKeyChecksDisabled('inventara_kustiba', 'kustiba_id', $id);

        return redirect('/inventara_kustiba')->with('success', $this->buildDeleteMessage('Inventāra kustības', []));
    }

    private function isParvietosanaMovement($kustibasVeidsId): bool
    {
        if (empty($kustibasVeidsId)) {
            return false;
        }

        $nosaukums = KustibasVeidi::query()
            ->where('kustibas_veids_id', $kustibasVeidsId)
            ->value('nosaukums');

        if (! is_string($nosaukums)) {
            return false;
        }

        $normalized = mb_strtolower($nosaukums, 'UTF-8');

        return str_contains($normalized, 'pārvietošan') || str_contains($normalized, 'parvietosan');
    }
}
