<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventaraKustiba;
use App\Models\Inventar;
use App\Models\KustibasVeidi;
use App\Models\Lietotajs;
use Illuminate\Support\Facades\DB;

class InventaraKustibaController extends Controller
{
    public function showAllKustiba(Request $request)
    {
        $q = trim($request->input('q', ''));
        $sort = $request->input('sort', 'kustiba_id');
        $direction = strtolower($request->input('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        $allowedSort = ['kustiba_id', 'datums'];
        if (!in_array($sort, $allowedSort, true)) {
            $sort = 'kustiba_id';
        }

        $query = InventaraKustiba::with(['inventars', 'lietotajs', 'kustibasVeids']);

        if ($q !== '') {
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
                    });
            });
        }

        $kustibas = $query->orderBy($sort, $direction)->get();

        return view('inventara_kustiba', compact('kustibas', 'sort', 'direction', 'q'));
    }

    public function createKustiba()
    {
        $inventari = Inventar::orderBy('inventars_id','asc')->get();
        $kustibasVeidi = KustibasVeidi::orderBy('kustibas_veids_id','asc')->get();
        $lietotaji = Lietotajs::orderBy('lietotajs_id','asc')->get();
        return view('createInventaraKustiba', ['inventari' => $inventari, 'kustibasVeidi' => $kustibasVeidi, 'lietotaji' => $lietotaji]);
    }

    public function KustibaSubmit(Request $req)
    {
        $data = $req->validate([
            'datums' => 'required|date',
            'inventars_id' => 'required|integer|exists:inventars,inventars_id',
            'kustibas_veids_id' => 'nullable|integer|exists:kustibas_veidi,kustibas_veids_id',
            'atbildigais_lietotajs_id' => 'required|integer|exists:lietotajs,lietotajs_id',
        ]);

        $i = new InventaraKustiba();
        $i->datums = $data['datums'];
        $i->inventars_id = $data['inventars_id'];
        $i->kustibas_veids_id = $data['kustibas_veids_id'] ?? null;
        $i->atbildigais_lietotajs_id = $data['atbildigais_lietotajs_id'];
        $i->save();

        return redirect()->to('/inventara_kustiba')->with('success','Ieraksts pievienots');
    }

    public function KustibaDetails($id)
    {
        $i = InventaraKustiba::with(['inventars','lietotajs','kustibasVeids'])->find($id);
        return view('detailsInventaraKustiba', ['kustiba' => $i]);
    }

    public function KustibaEdit($id)
    {
        $i = InventaraKustiba::find($id);
        $inventari = Inventar::orderBy('inventars_id','asc')->get();
        $kustibasVeidi = KustibasVeidi::orderBy('kustibas_veids_id','asc')->get();
        $lietotaji = Lietotajs::orderBy('lietotajs_id','asc')->get();
        return view('editInventaraKustiba', ['kustiba' => $i, 'inventari' => $inventari, 'kustibasVeidi' => $kustibasVeidi, 'lietotaji' => $lietotaji]);
    }

    public function editSubmit(Request $req, $id)
    {
        $data = $req->validate([
            'datums' => 'required|date',
            'inventars_id' => 'required|integer|exists:inventars,inventars_id',
            'kustibas_veids_id' => 'nullable|integer|exists:kustibas_veidi,kustibas_veids_id',
            'atbildigais_lietotajs_id' => 'required|integer|exists:lietotajs,lietotajs_id',
        ]);

        DB::table('inventara_kustiba')->where('kustiba_id',$id)->update([
            'datums' => $data['datums'],
            'inventars_id' => $data['inventars_id'],
            'kustibas_veids_id' => $data['kustibas_veids_id'] ?? null,
            'atbildigais_lietotajs_id' => $data['atbildigais_lietotajs_id'],
        ]);
        return redirect()->to('/inventara_kustiba')->with('success','Ieraksts atjaunināts');
    }

    public function KustibaDelete($id)
    {
        DB::table('inventara_kustiba')->where('kustiba_id',$id)->delete();
        return redirect('/inventara_kustiba')->with('success','Ieraksts dzēsts');
    }
}
