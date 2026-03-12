<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Telpa;
use Illuminate\Support\Facades\DB;

class TelpaController extends Controller
{
    // Rāda visu telpu sarakstu
    public function showAllTelpa(Request $request)
    {
        $q = trim($request->input('q', ''));
        $sort = $request->input('sort', 'telpas_id');
        $direction = strtolower($request->input('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        $allowedSort = ['telpas_id', 'nosaukums', 'izmeri', 'numurs', 'stavs'];
        if (!in_array($sort, $allowedSort, true)) {
            $sort = 'telpas_id';
        }

        $query = Telpa::query();

        if ($q !== '') {
            $query->where(function ($query) use ($q) {
                $query->where('nosaukums', 'like', "%{$q}%")
                    ->orWhere('izmeri', 'like', "%{$q}%")
                    ->orWhere('numurs', 'like', "%{$q}%")
                    ->orWhere('stavs', 'like', "%{$q}%");
            });
        }

        $telpas = $query->orderBy($sort, $direction)->get();

        return view('telpa', compact('telpas', 'sort', 'direction', 'q'));
    }

    // forma jaunas telpas izveidei
    public function createTelpa()
    {
        return view('createTelpa');
    }

    // saglabā jaunu ierakstu
    public function TelpaSubmit(Request $req)
    {
        $data = $req->validate([
            'nosaukums' => 'required|string|max:50',
            'izmeri' => 'nullable|string|max:10',
            'numurs' => 'nullable|integer',
            'stavs' => 'required|integer',
        ]);

        $t = new Telpa();
        $t->nosaukums = $data['nosaukums'];
        $t->izmeri = $data['izmeri'] ?? null;
        $t->numurs = $data['numurs'] ?? null;
        $t->stavs = $data['stavs'];
        $t->save();

        return redirect()->to('/telpa')->with('success','Ieraksts pievienots');
    }

    // detalizēta informācija
    public function TelpaDetails($id)
    {
        $t = Telpa::find($id);
        return view('detailsTelpa', ['telpa' => $t]);
    }

    public function TelpaEdit($id)
    {
        $t = Telpa::find($id);
        return view('editTelpa', ['telpa' => $t]);
    }

    public function editSubmit(Request $req, $id)
    {
        $data = $req->validate([
            'nosaukums' => 'required|string|max:50',
            'izmeri' => 'nullable|string|max:10',
            'numurs' => 'nullable|integer',
            'stavs' => 'required|integer',
        ]);

        DB::table('telpa')
            ->where('telpas_id',$id)
            ->update([
                'nosaukums' => $data['nosaukums'],
                'izmeri' => $data['izmeri'] ?? null,
                'numurs' => $data['numurs'] ?? null,
                'stavs' => $data['stavs'],
            ]);

        return redirect()->to('/telpa')->with('success','Ieraksts atjaunināts');
    }

    public function TelpaDelete($id)
    {
        DB::table('telpa')->where('telpas_id',$id)->delete();
        return redirect('/telpa')->with('success','Ieraksts dzēsts');
    }
}
