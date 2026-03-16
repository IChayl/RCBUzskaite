<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lietotajs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

// Kontrolieris lietotāju pārvaldībai (CRUD darbības).
class LietotajsController extends Controller
{
    /**
     * Parāda visu lietotāju sarakstu.
     */
    public function showAllLietotaji()
    {
        $u = new Lietotajs();
        return view('lietotaji', ['lietotaji' => $u->orderBy('lietotajs_id','asc')->get()]);
    }

    /**
     * Atver lietotāja izveides formu (tikai administratoram).
     */
    public function createLietotajs()
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        return view('createLietotajs');
    }

    /**
     * Validē ievadi un izveido jaunu lietotāja ierakstu.
     */
    public function LietotajsSubmit(Request $req)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $req->validate([
            'lietotajvards' => 'required|string|max:255',
            'parole' => 'required|string|max:255',
            'avatar' => 'nullable|image|max:2048',
            'vards' => 'nullable|string|max:50',
            'uzvards' => 'nullable|string|max:50',
            'epasts' => 'nullable|email|max:100',
            'telefons' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'amats' => 'nullable|string|max:50',
            'aktivs' => 'nullable|boolean',
        ]);

        $u = new Lietotajs();
        $u->lietotajvards = $req->input('lietotajvards');
        $u->parole = $req->input('parole');
        $u->admina_tiesibas = $req->input('admina_tiesibas') ? 1 : 0;
        $u->vards = $req->input('vards');
        $u->uzvards = $req->input('uzvards');
        $u->epasts = $req->input('epasts');
        $u->telefons = $req->input('telefons');
        $u->amats = $req->input('amats');
        $u->aktivs = $req->input('aktivs') ? 1 : 0;

        // Ja augšupielādēts attēls, saglabā to publiskajā diskā.
        if ($req->hasFile('avatar')) {
            Storage::disk('public')->makeDirectory('avatars');
            $path = $req->file('avatar')->store('avatars', 'public');
            $u->avatar = $path;
        }

        $u->save();
        return redirect()->to('/lietotajs')->with('success','Ieraksts pievienots');
    }

    /**
     * Parāda lietotāja detalizēto skatu.
     */
    public function LietotajsDetails($id)
    {
        $u = Lietotajs::find($id);
        return view('detailsLietotajs', ['lietotajs' => $u]);
    }

    /**
     * Atver lietotāja rediģēšanas formu (tikai administratoram).
     */
    public function LietotajsEdit($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $u = Lietotajs::find($id);
        return view('editLietotajs', ['lietotajs' => $u]);
    }

    /**
     * Validē un saglabā lietotāja izmaiņas.
     */
    public function editSubmit(Request $req, $id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $req->validate([
            'lietotajvards' => 'required|string|max:255',
            'parole' => 'required|string|max:255',
            'avatar' => 'nullable|image|max:2048',
            'vards' => 'nullable|string|max:50',
            'uzvards' => 'nullable|string|max:50',
            'epasts' => 'nullable|email|max:100',
            'telefons' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'amats' => 'nullable|string|max:50',
            'aktivs' => 'nullable|boolean',
        ]);

        $data = [
            'lietotajvards' => $req->input('lietotajvards'),
            'parole' => $req->input('parole'),
            'admina_tiesibas' => $req->input('admina_tiesibas') ? 1 : 0,
            'vards' => $req->input('vards'),
            'uzvards' => $req->input('uzvards'),
            'epasts' => $req->input('epasts'),
            'telefons' => $req->input('telefons'),
            'amats' => $req->input('amats'),
            'aktivs' => $req->input('aktivs') ? 1 : 0,
        ];

        // Ja pievienots jauns avatar attēls, aizvieto ceļu ar jauno failu.
        if ($req->hasFile('avatar')) {
            Storage::disk('public')->makeDirectory('avatars');
            $path = $req->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        DB::table('lietotajs')->where('lietotajs_id',$id)->update($data);
        return redirect()->to('/lietotajs')->with('success','Ieraksts atjaunināts');
    }

    /**
     * Atgriež lietotāja avatar attēlu tieši no publiskā diska.
     */
    public function avatar($id)
    {
        $lietotajs = Lietotajs::findOrFail($id);
        $avatarPath = $lietotajs->resolveAvatarPath();

        if (! $avatarPath) {
            abort(404);
        }

        return response()->file(Storage::disk('public')->path($avatarPath));
    }

    /**
     * Dzēš lietotāja ierakstu (tikai administratoram).
     */
    public function LietotajsDelete($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        DB::table('lietotajs')->where('lietotajs_id',$id)->delete();
        return redirect('/lietotajs')->with('success','Ieraksts dzēsts');
    }
}
