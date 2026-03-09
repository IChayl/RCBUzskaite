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
    public function showAllKustiba()
    {
        $kustibas = InventaraKustiba::with(['inventars','lietotajs','kustibasVeids'])->orderBy('kustiba_id','asc')->get();
        return view('inventara_kustiba', ['kustibas' => $kustibas]);
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
