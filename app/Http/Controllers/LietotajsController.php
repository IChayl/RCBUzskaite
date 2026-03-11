<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lietotajs;
use Illuminate\Support\Facades\DB;

class LietotajsController extends Controller
{
    public function showAllLietotaji()
    {
        $u = new Lietotajs();
        return view('lietotaji', ['lietotaji' => $u->orderBy('lietotajs_id','asc')->get()]);
    }

    public function createLietotajs()
    {
        return view('createLietotajs');
    }

    public function LietotajsSubmit(Request $req)
    {
        $req->validate([
            'lietotajvards' => 'required|string|max:255',
            'parole' => 'required|string|max:255',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $u = new Lietotajs();
        $u->lietotajvards = $req->input('lietotajvards');
        $u->parole = $req->input('parole');
        $u->admina_tiesibas = $req->input('admina_tiesibas') ? 1 : 0;

        if ($req->hasFile('avatar')) {
            $path = $req->file('avatar')->store('avatars', 'public');
            $u->avatar = $path;
        }

        $u->save();
        return redirect()->to('/lietotajs')->with('success','Ieraksts pievienots');
    }

    public function LietotajsDetails($id)
    {
        $u = Lietotajs::find($id);
        return view('detailsLietotajs', ['lietotajs' => $u]);
    }

    public function LietotajsEdit($id)
    {
        $u = Lietotajs::find($id);
        return view('editLietotajs', ['lietotajs' => $u]);
    }

    public function editSubmit(Request $req, $id)
    {
        $req->validate([
            'lietotajvards' => 'required|string|max:255',
            'parole' => 'required|string|max:255',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $data = [
            'lietotajvards' => $req->input('lietotajvards'),
            'parole' => $req->input('parole'),
            'admina_tiesibas' => $req->input('admina_tiesibas') ? 1 : 0,
        ];

        if ($req->hasFile('avatar')) {
            $path = $req->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        DB::table('lietotajs')->where('lietotajs_id',$id)->update($data);
        return redirect()->to('/lietotajs')->with('success','Ieraksts atjaunināts');
    }

    public function LietotajsDelete($id)
    {
        DB::table('lietotajs')->where('lietotajs_id',$id)->delete();
        return redirect('/lietotajs')->with('success','Ieraksts dzēsts');
    }
}
