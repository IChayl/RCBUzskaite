<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AtrasanasVieta;
use Illuminate\Support\Facades\DB;

class AtrasanasVietaController extends Controller
{
    // Rāda visu vietu sarakstu
    public function showAllVieta()
    {
        $v = new AtrasanasVieta();
        return view('atrasanas_vieta', ['vietas' => $v->orderBy('atrasanas_vieta_id','asc')->get()]);
    }

    // forma jaunas vietas izveidei
    public function createVieta()
    {
        return view('createAtrasanasVieta');
    }

    // saglabā jaunu ierakstu
    public function VietaSubmit(Request $req)
    {
        $v = new AtrasanasVieta();
        $v->nodala = $req->input('nodala');
        $v->telpas_id = $req->input('telpas_id');
        $v->stavs = $req->input('stavs');
        $v->save();

        return redirect()->to('/atrasanas_vieta')->with('success','Ieraksts pievienots');
    }

    // detalizēta informācija
    public function VietaDetails($id)
    {
        $v = AtrasanasVieta::find($id);
        return view('detailsAtrasanasVieta', ['vieta' => $v]);
    }

    public function VietaEdit($id)
    {
        $v = AtrasanasVieta::find($id);
        return view('editAtrasanasVieta', ['vieta' => $v]);
    }

    public function editSubmit(Request $req, $id)
    {
        DB::table('atrasanas_vieta')
            ->where('atrasanas_vieta_id',$id)
            ->update([
                'nodala' => $req->input('nodala'),
                'telpas_id' => $req->input('telpas_id'),
                'stavs' => $req->input('stavs'),
            ]);

        return redirect()->to('/atrasanas_vieta')->with('success','Ieraksts atjaunināts');
    }

    public function VietaDelete($id)
    {
        DB::table('atrasanas_vieta')->where('atrasanas_vieta_id',$id)->delete();
        return redirect('/atrasanas_vieta')->with('success','Ieraksts dzēsts');
    }
}
