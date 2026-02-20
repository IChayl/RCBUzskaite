<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventar;
use Illuminate\Support\Facades\DB;

class InventarsController extends Controller
{
    public function showAllInventars()
    {
        $i = new Inventar();
        return view('inventars', ['inventari' => $i->orderBy('inventars_id','asc')->get()]);
    }

    public function createInventar()
    {
        return view('createInventar');
    }

    public function InventarSubmit(Request $req)
    {
        $i = new Inventar();
        $i->nosaukums = $req->input('nosaukums');
        $i->apraksts = $req->input('apraksts');
        $i->nolietojums = $req->input('nolietojums');
        $i->statuss = $req->input('statuss');
        $i->kategorija_id = $req->input('kategorija_id');
        $i->atrasanas_vieta_id = $req->input('atrasanas_vieta_id');
        $i->save();
        return redirect()->to('/inventars')->with('success','Ieraksts pievienots');
    }

    public function InventarDetails($id)
    {
        $i = Inventar::find($id);
        return view('detailsInventar', ['inventar' => $i]);
    }

    public function InventarEdit($id)
    {
        $i = Inventar::find($id);
        return view('editInventar', ['inventar' => $i]);
    }

    public function editSubmit(Request $req, $id)
    {
        DB::table('inventars')->where('inventars_id',$id)->update([
            'nosaukums' => $req->input('nosaukums'),
            'apraksts' => $req->input('apraksts'),
            'nolietojums' => $req->input('nolietojums'),
            'statuss' => $req->input('statuss'),
            'kategorija_id' => $req->input('kategorija_id'),
            'atrasanas_vieta_id' => $req->input('atrasanas_vieta_id'),
        ]);
        return redirect()->to('/inventars')->with('success','Ieraksts atjaunināts');
    }

    public function InventarDelete($id)
    {
        DB::table('inventars')->where('inventars_id',$id)->delete();
        return redirect('/inventars')->with('success','Ieraksts dzēsts');
    }
}
