<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventar;
use App\Models\Telpa;
use App\Models\KategorijaModel;
use App\Models\Lietotajs;
use Illuminate\Support\Facades\DB;

// Kontrolieris inventāra ierakstu sarakstam, izveidei, labošanai un dzēšanai.
class InventarsController extends Controller
{
    /**
     * Parāda inventāra sarakstu ar meklēšanu, kārtošanu un lapošanu.
     */
    public function showAllInventars(Request $request)
    {
        // Meklēšanas teksta un kolonnas iestatījumi
        $q = trim($request->input('q', ''));
        $column = $request->input('column', 'all');

        // Kārtošanas parametri
        $sort = $request->input('sort', 'inventars_id');
        $direction = strtolower($request->input('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        // Atļautās kolonnas kārtošanai
        $allowedSort = ['inventars_id', 'nosaukums', 'apraksts', 'statuss', 'kategorija', 'telpa', 'atbildigais'];
        if (!in_array($sort, $allowedSort, true)) {
            $sort = 'inventars_id';
        }

        // Atļautās kolonnas meklēšanai
        $allowedColumns = ['all', 'nosaukums', 'apraksts', 'statuss', 'kategorija', 'telpa', 'atbildigais'];
        if (!in_array($column, $allowedColumns, true)) {
            $column = 'all';
        }

        $query = Inventar::query()
            ->with(['kategorija', 'telpa', 'atbildigais']);

        // Meklēšana kolonnā vai visās kolonnās
        if ($q !== '') {
            if ($column === 'all') {
                $query->where(function ($query) use ($q) {
                    $query->where('nosaukums', 'like', "%{$q}%")
                        ->orWhere('apraksts', 'like', "%{$q}%")
                        ->orWhere('statuss', 'like', "%{$q}%")
                        ->orWhereHas('kategorija', function ($q2) use ($q) {
                            $q2->where('nosaukums', 'like', "%{$q}%");
                        })
                        ->orWhereHas('telpa', function ($q2) use ($q) {
                            $q2->where('nosaukums', 'like', "%{$q}%");
                        })
                        ->orWhereHas('atbildigais', function ($q2) use ($q) {
                            $q2->where('lietotajvards', 'like', "%{$q}%");
                        })
                        ->orWhere('inventara_numurs', 'like', "%{$q}%")
                        ->orWhere('iegades_datums', 'like', "%{$q}%");
                });
            } elseif (in_array($column, ['nosaukums', 'apraksts', 'statuss'], true)) {
                $query->where($column, 'like', "%{$q}%");
            } elseif ($column === 'kategorija') {
                $query->whereHas('kategorija', function ($q2) use ($q) {
                    $q2->where('nosaukums', 'like', "%{$q}%");
                });
            } elseif ($column === 'telpa') {
                $query->whereHas('telpa', function ($q2) use ($q) {
                    $q2->where('nosaukums', 'like', "%{$q}%");
                });
            } elseif ($column === 'atbildigais') {
                $query->whereHas('atbildigais', function ($q2) use ($q) {
                    $q2->where('lietotajvards', 'like', "%{$q}%");
                });
            }
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
        $inventari = $query->paginate(15)->withQueryString();

        return view('inventars', compact('inventari', 'sort', 'direction', 'q', 'column'));
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
            'apraksts' => 'nullable|string|max:200',
            'statuss' => 'nullable|string|max:25',
            'kategorija_id' => 'required|integer|exists:kategorija,kategorija_id',
            'telpas_id' => 'required|integer|exists:telpa,telpas_id',
            'atbildigais_id' => 'nullable|integer|exists:lietotajs,lietotajs_id',
            'inventara_numurs' => 'nullable|string|max:50',
            'iegades_datums' => 'nullable|date',
        ]);

        // Izveido ierakstu
        $i = new Inventar();
        $i->nosaukums = $data['nosaukums'];
        $i->apraksts = $data['apraksts'] ?? null;
        $i->statuss = $data['statuss'] ?? null;
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
        $i = Inventar::find($id);
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
            'apraksts' => 'nullable|string|max:200',
            'statuss' => 'nullable|string|max:25',
            'kategorija_id' => 'required|integer|exists:kategorija,kategorija_id',
            'telpas_id' => 'required|integer|exists:telpa,telpas_id',
            'atbildigais_id' => 'nullable|integer|exists:lietotajs,lietotajs_id',
            'inventara_numurs' => 'nullable|string|max:50',
            'iegades_datums' => 'nullable|date',
        ]);

        DB::table('inventars')->where('inventars_id',$id)->update([
            'nosaukums' => $data['nosaukums'],
            'apraksts' => $data['apraksts'] ?? null,
            'statuss' => $data['statuss'] ?? null,
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
        DB::table('inventars')->where('inventars_id',$id)->delete();
        return redirect('/inventars')->with('success','Ieraksts dzēsts');
    }
}
