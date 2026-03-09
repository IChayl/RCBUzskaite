<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KustibasVeidi;
use Illuminate\Support\Facades\DB;

class KustibasVeidiController extends Controller
{
    public function showAll()
    {
        $veidi = KustibasVeidi::orderBy('kustibas_veids_id','asc')->get();
        return view('kustibas_veidi', ['veidi' => $veidi]);
    }

    public function create()
    {
        return view('createKustibasVeidi');
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'nosaukums' => 'required|string|max:50',
            'apraksts' => 'nullable|string|max:200',
        ]);

        $veids = new KustibasVeidi();
        $veids->nosaukums = $data['nosaukums'];
        $veids->apraksts = $data['apraksts'] ?? null;
        $veids->save();

        return redirect()->to('/kustibas_veidi')->with('success','Ieraksts pievienots');
    }

    public function details($id)
    {
        $veids = KustibasVeidi::find($id);
        return view('detailsKustibasVeidi', ['veids' => $veids]);
    }

    public function edit($id)
    {
        $veids = KustibasVeidi::find($id);
        return view('editKustibasVeidi', ['veids' => $veids]);
    }

    public function update(Request $req, $id)
    {
        $data = $req->validate([
            'nosaukums' => 'required|string|max:50',
            'apraksts' => 'nullable|string|max:200',
        ]);

        DB::table('kustibas_veidi')->where('kustibas_veids_id', $id)->update([
            'nosaukums' => $data['nosaukums'],
            'apraksts' => $data['apraksts'] ?? null,
        ]);

        return redirect()->to('/kustibas_veidi')->with('success','Ieraksts atjaunināts');
    }

    public function delete($id)
    {
        DB::table('kustibas_veidi')->where('kustibas_veids_id', $id)->delete();
        return redirect('/kustibas_veidi')->with('success','Ieraksts dzēsts');
    }
}
