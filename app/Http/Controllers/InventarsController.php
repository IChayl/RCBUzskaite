<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventar;
use App\Models\Telpa;
use App\Models\KategorijaModel;
use App\Models\Lietotajs;
use Illuminate\Support\Facades\DB;

class InventarsController extends Controller
{
    public function showAllInventars()
    {
        $inventari = Inventar::with(['kategorija','telpa','atbildigais'])->orderBy('inventars_id','asc')->get();
        return view('inventars', ['inventari' => $inventari]);
    }

    public function createInventar()
    {
        $telpas = Telpa::orderBy('telpas_id','asc')->get();
        $kategorijas = KategorijaModel::orderBy('kategorija_id','asc')->get();
        $lietotaji = Lietotajs::orderBy('lietotajs_id','asc')->get();
        return view('createInventar', ['telpas' => $telpas, 'kategorijas' => $kategorijas, 'lietotaji' => $lietotaji]);
    }

    public function InventarSubmit(Request $req)
    {
        $data = $req->validate([
            'nosaukums' => 'required|string|max:30',
            'apraksts' => 'nullable|string|max:200',
            'nolietojums' => 'nullable|string|max:25',
            'statuss' => 'nullable|string|max:25',
            'kategorija_id' => 'required|integer|exists:kategorija,kategorija_id',
            'telpas_id' => 'required|integer|exists:telpa,telpas_id',
            'atbildigais_id' => 'nullable|integer|exists:lietotajs,lietotajs_id',
        ]);

        // create the record
        $i = new Inventar();
        $i->nosaukums = $data['nosaukums'];
        $i->apraksts = $data['apraksts'] ?? null;
        $i->nolietojums = $data['nolietojums'] ?? null;
        $i->statuss = $data['statuss'] ?? null;
        $i->kategorija_id = $data['kategorija_id'];
        $i->telpas_id = $data['telpas_id'];
        $i->atbildigais_id = $data['atbildigais_id'] ?? null;
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
        $telpas = Telpa::orderBy('telpas_id','asc')->get();
        $kategorijas = KategorijaModel::orderBy('kategorija_id','asc')->get();
        $lietotaji = Lietotajs::orderBy('lietotajs_id','asc')->get();
        return view('editInventar', ['inventar' => $i, 'telpas' => $telpas, 'kategorijas' => $kategorijas, 'lietotaji' => $lietotaji]);
    }

    public function editSubmit(Request $req, $id)
    {
        $data = $req->validate([
            'nosaukums' => 'required|string|max:30',
            'apraksts' => 'nullable|string|max:200',
            'nolietojums' => 'nullable|string|max:25',
            'statuss' => 'nullable|string|max:25',
            'kategorija_id' => 'required|integer|exists:kategorija,kategorija_id',
            'telpas_id' => 'required|integer|exists:telpa,telpas_id',
            'atbildigais_id' => 'nullable|integer|exists:lietotajs,lietotajs_id',
        ]);

        DB::table('inventars')->where('inventars_id',$id)->update([
            'nosaukums' => $data['nosaukums'],
            'apraksts' => $data['apraksts'] ?? null,
            'nolietojums' => $data['nolietojums'] ?? null,
            'statuss' => $data['statuss'] ?? null,
            'kategorija_id' => $data['kategorija_id'],
            'telpas_id' => $data['telpas_id'],
            'atbildigais_id' => $data['atbildigais_id'] ?? null,
        ]);

        return redirect()->to('/inventars')->with('success','Ieraksts atjaunināts');
    }

    public function InventarDelete($id)
    {
        DB::table('inventars')->where('inventars_id',$id)->delete();
        return redirect('/inventars')->with('success','Ieraksts dzēsts');
    }
}
