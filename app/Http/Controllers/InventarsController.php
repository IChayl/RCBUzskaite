<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventar;
use App\Models\AtrasanasVieta;
use App\Models\KategorijaModel;
use Illuminate\Support\Facades\DB;

class InventarsController extends Controller
{
    public function showAllInventars()
    {
        $inventari = Inventar::with(['kategorija','vieta'])->orderBy('inventars_id','asc')->get();
        return view('inventars', ['inventari' => $inventari]);
    }

    public function createInventar()
    {
        $vietas = AtrasanasVieta::orderBy('atrasanas_vieta_id','asc')->get();
        $kategorijas = KategorijaModel::orderBy('kategorija_id','asc')->get();
        return view('createInventar', ['vietas' => $vietas, 'kategorijas' => $kategorijas]);
    }

    public function InventarSubmit(Request $req)
    {
        $data = $req->validate([
            'nosaukums' => 'required|string|max:30',
            'apraksts' => 'nullable|string|max:200',
            'nolietojums' => 'nullable|string|max:25',
            'statuss' => 'nullable|string|max:25',
            'kategorija_id' => 'required|integer',
            'atrasanas_vieta_id' => 'required|integer|exists:atrasanas_vieta,atrasanas_vieta_id',
        ]);

        // create the record
        $i = new Inventar();
        $i->nosaukums = $data['nosaukums'];
        $i->apraksts = $data['apraksts'] ?? null;
        $i->nolietojums = $data['nolietojums'] ?? null;
        $i->statuss = $data['statuss'] ?? null;
        $i->kategorija_id = $data['kategorija_id'];
        $i->atrasanas_vieta_id = $data['atrasanas_vieta_id'];
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
        $vietas = AtrasanasVieta::orderBy('atrasanas_vieta_id','asc')->get();
        $kategorijas = KategorijaModel::orderBy('kategorija_id','asc')->get();
        return view('editInventar', ['inventar' => $i, 'vietas' => $vietas, 'kategorijas' => $kategorijas]);
    }

    public function editSubmit(Request $req, $id)
    {
        $data = $req->validate([
            'nosaukums' => 'required|string|max:30',
            'apraksts' => 'nullable|string|max:200',
            'nolietojums' => 'nullable|string|max:25',
            'statuss' => 'nullable|string|max:25',
            'kategorija_id' => 'required|integer',
            'atrasanas_vieta_id' => 'required|integer|exists:atrasanas_vieta,atrasanas_vieta_id',
        ]);

        DB::table('inventars')->where('inventars_id',$id)->update([
            'nosaukums' => $data['nosaukums'],
            'apraksts' => $data['apraksts'] ?? null,
            'nolietojums' => $data['nolietojums'] ?? null,
            'statuss' => $data['statuss'] ?? null,
            'kategorija_id' => $data['kategorija_id'],
            'atrasanas_vieta_id' => $data['atrasanas_vieta_id'],
        ]);

        return redirect()->to('/inventars')->with('success','Ieraksts atjaunināts');
    }

    public function InventarDelete($id)
    {
        DB::table('inventars')->where('inventars_id',$id)->delete();
        return redirect('/inventars')->with('success','Ieraksts dzēsts');
    }
}
