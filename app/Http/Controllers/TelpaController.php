<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Telpa;
use Illuminate\Support\Facades\DB;

class TelpaController extends Controller
{
    // Rāda visu telpu sarakstu
    public function showAllTelpa()
    {
        $t = new Telpa();
        return view('telpa', ['telpas' => $t->orderBy('telpas_id','asc')->get()]);
    }

    // forma jaunas telpas izveidei
    public function createTelpa()
    {
        return view('createTelpa');
    }

    // saglabā jaunu ierakstu
    public function TelpaSubmit(Request $req)
    {
        $t = new Telpa();
        $t->nosaukums = $req->input('nosaukums');
        $t->izmeri = $req->input('izmeri');
        $t->numurs = $req->input('numurs');
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
        DB::table('telpa')
            ->where('telpas_id',$id)
            ->update([
                'nosaukums' => $req->input('nosaukums'),
                'izmeri' => $req->input('izmeri'),
                'numurs' => $req->input('numurs'),
            ]);

        return redirect()->to('/telpa')->with('success','Ieraksts atjaunināts');
    }

    public function TelpaDelete($id)
    {
        DB::table('telpa')->where('telpas_id',$id)->delete();
        return redirect('/telpa')->with('success','Ieraksts dzēsts');
    }
}
