<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventaraKustiba;
use Illuminate\Support\Facades\DB;

class InventaraKustibaController extends Controller
{
    public function showAllKustiba()
    {
        $i = new InventaraKustiba();
        return view('inventara_kustiba', ['kustibas' => $i->orderBy('kustiba_id','asc')->get()]);
    }

    public function createKustiba()
    {
        return view('createInventaraKustiba');
    }

    public function KustibaSubmit(Request $req)
    {
        $i = new InventaraKustiba();
        $i->datums = $req->input('datums');
        $i->inventars_id = $req->input('inventars_id');
        $i->no_atrasanas_vietas_id = $req->input('no_atrasanas_vietas_id');
        $i->uz_atrasanas_vietas_id = $req->input('uz_atrasanas_vietas_id');
        $i->atbildigais_lietotajs_id = $req->input('atbildigais_lietotajs_id');
        $i->save();

        return redirect()->to('/inventara_kustiba')->with('success','Ieraksts pievienots');
    }

    public function KustibaDetails($id)
    {
        $i = InventaraKustiba::find($id);
        return view('detailsInventaraKustiba', ['kustiba' => $i]);
    }

    public function KustibaEdit($id)
    {
        $i = InventaraKustiba::find($id);
        return view('editInventaraKustiba', ['kustiba' => $i]);
    }

    public function editSubmit(Request $req, $id)
    {
        DB::table('inventara_kustiba')->where('kustiba_id',$id)->update([
            'datums' => $req->input('datums'),
            'inventars_id' => $req->input('inventars_id'),
            'no_atrasanas_vietas_id' => $req->input('no_atrasanas_vietas_id'),
            'uz_atrasanas_vietas_id' => $req->input('uz_atrasanas_vietas_id'),
            'atbildigais_lietotajs_id' => $req->input('atbildigais_lietotajs_id'),
        ]);
        return redirect()->to('/inventara_kustiba')->with('success','Ieraksts atjaunināts');
    }

    public function KustibaDelete($id)
    {
        DB::table('inventara_kustiba')->where('kustiba_id',$id)->delete();
        return redirect('/inventara_kustiba')->with('success','Ieraksts dzēsts');
    }
}
