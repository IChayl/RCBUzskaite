<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventaraKustiba;
use App\Models\Inventar;
use App\Models\KustibasVeidi;
use App\Models\Lietotajs;
use App\Models\Telpa;
use Illuminate\Support\Facades\DB;

// Kontrolieris inventāra kustību pārvaldībai.
class InventaraKustibaController extends Controller
{
    /**
     * Parāda kustību sarakstu ar filtrēšanu, kārtošanu un lapošanu.
     */
    public function showAllKustiba(Request $request)
    {
        // Meklēšanas teksts un izvēlētā kolonna (vai "all" līdz meklēšanai visur)
        $q = trim($request->input('q', ''));
        $column = $request->input('column', 'all');

        // Kārtošanas iestatījumi: kolonna un virziens (asc/desc)
        $sort = $request->input('sort', 'kustiba_id');
        $direction = strtolower($request->input('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        // Drošība: atļautās kolonnas, pēc kurām var kārtot
        $allowedSort = ['kustiba_id', 'datums', 'inventars', 'kustibas_veids', 'lietotajs', 'veca_telpa', 'jauna_telpa'];
        if (!in_array($sort, $allowedSort, true)) {
            $sort = 'kustiba_id';
        }

        // Atļautās kolonnas meklēšanai.
        $allowedColumns = ['all', 'datums', 'inventars', 'kustibas_veids', 'lietotajs', 'veca_telpa', 'jauna_telpa'];
        if (!in_array($column, $allowedColumns, true)) {
            $column = 'all';
        }

        // Ielādējam saistītos modeļus, lai samazinātu papildus SQL pieprasījumus skatā.
        $query = InventaraKustiba::query()->with(['inventars', 'lietotajs', 'kustibasVeids']);

        // Meklēšanas loģika pa vienu vai visām kolonnām.
        if ($q !== '') {
            if ($column === 'all') {
                $query->where(function ($query) use ($q) {
                    $query->where('datums', 'like', "%{$q}%")
                        ->orWhereHas('inventars', function ($q2) use ($q) {
                            $q2->where('nosaukums', 'like', "%{$q}%");
                        })
                        ->orWhereHas('kustibasVeids', function ($q2) use ($q) {
                            $q2->where('nosaukums', 'like', "%{$q}%");
                        })
                                ->orWhereHas('lietotajs', function ($q2) use ($q) {
                            $q2->where('lietotajvards', 'like', "%{$q}%");
                        })
                        ->orWhereHas('vecaTelpa', function ($q2) use ($q) {
                            $q2->where('nosaukums', 'like', "%{$q}%");
                        })
                        ->orWhereHas('jaunaTelpa', function ($q2) use ($q) {
                            $q2->where('nosaukums', 'like', "%{$q}%");
                        })
                        ->orWhere('piezimes', 'like', "%{$q}%")
                        ->orWhere('dokuments', 'like', "%{$q}%");
                });
            } elseif ($column === 'datums') {
                $query->where('datums', 'like', "%{$q}%");
            } elseif ($column === 'inventars') {
                $query->whereHas('inventars', function ($q2) use ($q) {
                    $q2->where('nosaukums', 'like', "%{$q}%");
                });
            } elseif ($column === 'kustibas_veids') {
                $query->whereHas('kustibasVeids', function ($q2) use ($q) {
                    $q2->where('nosaukums', 'like', "%{$q}%");
                });
            } elseif ($column === 'lietotajs') {
                $query->whereHas('lietotajs', function ($q2) use ($q) {
                    $q2->where('lietotajvards', 'like', "%{$q}%");
                });
            } elseif ($column === 'veca_telpa') {
                $query->whereHas('vecaTelpa', function ($q2) use ($q) {
                    $q2->where('nosaukums', 'like', "%{$q}%");
                });
            } elseif ($column === 'jauna_telpa') {
                $query->whereHas('jaunaTelpa', function ($q2) use ($q) {
                    $q2->where('nosaukums', 'like', "%{$q}%");
                });
            } elseif ($column === 'piezimes') {
                $query->where('piezimes', 'like', "%{$q}%");
            } elseif ($column === 'dokuments') {
                $query->where('dokuments', 'like', "%{$q}%");
            }
        }

        // Kārtošana pēc saistītajiem modeļiem (jāizmanto join, lai var kārtot pēc saistītajām tabulām)
        if ($sort === 'inventars') {
            $query->leftJoin('inventars', 'inventara_kustiba.inventars_id', '=', 'inventars.inventars_id')
                ->orderBy('inventars.nosaukums', $direction)
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

        $kustibas = $query->paginate(15)->withQueryString();

        return view('inventara_kustiba', compact('kustibas', 'sort', 'direction', 'q', 'column'));
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
        DB::table('inventara_kustiba')->where('kustiba_id',$id)->delete();
        return redirect('/inventara_kustiba')->with('success','Ieraksts dzēsts');
    }
}
