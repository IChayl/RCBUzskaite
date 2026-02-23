<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventaraKustiba;
use App\Models\Inventar;
use App\Models\AtrasanasVieta;
use App\Models\Lietotajs;
use Illuminate\Support\Facades\DB;

class InventaraKustibaController extends Controller
{
    public function showAllKustiba()
    {
        $kustibas = InventaraKustiba::with(['inventars','noVieta','uzVieta','lietotajs'])->orderBy('kustiba_id','asc')->get();
        return view('inventara_kustiba', ['kustibas' => $kustibas]);
    }

    public function createKustiba()
    {
        $inventari = Inventar::orderBy('inventars_id','asc')->get();
        $vietas = AtrasanasVieta::orderBy('atrasanas_vieta_id','asc')->get();
        $lietotaji = Lietotajs::orderBy('lietotajs_id','asc')->get();
        return view('createInventaraKustiba', ['inventari' => $inventari, 'vietas' => $vietas, 'lietotaji' => $lietotaji]);
    }

    public function KustibaSubmit(Request $req)
    {
        $data = $req->validate([
            'datums' => 'required|date',
            'inventars_id' => 'required|integer|exists:inventars,inventars_id',
            'no_atrasanas_vietas_id' => 'required|integer|exists:atrasanas_vieta,atrasanas_vieta_id',
            'uz_atrasanas_vietas_id' => 'required|integer|exists:atrasanas_vieta,atrasanas_vieta_id',
            'atbildigais_lietotajs_id' => 'required|integer|exists:lietotajs,lietotajs_id',
        ]);

        $i = new InventaraKustiba();
        $i->datums = $data['datums'];
        $i->inventars_id = $data['inventars_id'];
        $i->no_atrasanas_vietas_id = $data['no_atrasanas_vietas_id'];
        $i->uz_atrasanas_vietas_id = $data['uz_atrasanas_vietas_id'];
        $i->atbildigais_lietotajs_id = $data['atbildigais_lietotajs_id'];
        $i->save();

        return redirect()->to('/inventara_kustiba')->with('success','Ieraksts pievienots');
    }

    public function KustibaDetails($id)
    {
        $i = InventaraKustiba::with(['inventars','noVieta','uzVieta','lietotajs'])->find($id);
        return view('detailsInventaraKustiba', ['kustiba' => $i]);
    }

    public function KustibaEdit($id)
    {
        $i = InventaraKustiba::find($id);
        $inventari = Inventar::orderBy('inventars_id','asc')->get();
        $vietas = AtrasanasVieta::orderBy('atrasanas_vieta_id','asc')->get();
        $lietotaji = Lietotajs::orderBy('lietotajs_id','asc')->get();
        return view('editInventaraKustiba', ['kustiba' => $i, 'inventari' => $inventari, 'vietas' => $vietas, 'lietotaji' => $lietotaji]);
    }

    public function editSubmit(Request $req, $id)
    {
        $data = $req->validate([
            'datums' => 'required|date',
            'inventars_id' => 'required|integer|exists:inventars,inventars_id',
            'no_atrasanas_vietas_id' => 'required|integer|exists:atrasanas_vieta,atrasanas_vieta_id',
            'uz_atrasanas_vietas_id' => 'required|integer|exists:atrasanas_vieta,atrasanas_vieta_id',
            'atbildigais_lietotajs_id' => 'required|integer|exists:lietotajs,lietotajs_id',
        ]);

        DB::table('inventara_kustiba')->where('kustiba_id',$id)->update([
            'datums' => $data['datums'],
            'inventars_id' => $data['inventars_id'],
            'no_atrasanas_vietas_id' => $data['no_atrasanas_vietas_id'],
            'uz_atrasanas_vietas_id' => $data['uz_atrasanas_vietas_id'],
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
