<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Lietotajs;

class LoginController extends Controller
{
    /**
    * Parāda pieteikšanās formu.
     */
    public function showLogin()
    {
        return view('Login');
    }

    /**
     * Apstrādā formas iesniegšanu un validē lietotāja datus.
     */
    public function submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = Lietotajs::where('epasts', $request->input('email'))
            ->where('parole', $request->input('password'))
            ->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Nekorekts e-pasts vai parole'])->withInput();
        }

        Auth::login($user);
        $request->session()->regenerate();

        // Saglabā nelielu sesijas palīgu gadījumiem ārpus Auth izmantošanas.
        Session::put('user_name', $user->lietotajvards);

        return redirect('/home')->with('success', 'Pieteikšanās veiksmīga');
    }

    /**
     * Parāda reģistrācijas formu.
     */
    public function showRegister()
    {
        return view('Register');
    }

    /**
     * Apstrādā reģistrāciju un saglabā jaunu Lietotajs ierakstu.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:lietotajs,lietotajvards',
            'password' => 'required|string|min:4',
        ]);

        $user = Lietotajs::create([
            'lietotajvards' => $request->input('name'),
            'parole' => $request->input('password'),
            'admina_tiesibas' => 0,
        ]);

        Session::put('user_id', $user->lietotajs_id);
        Session::put('user_name', $user->lietotajvards);

        return redirect('/')->with('success', 'Reģistrācija veiksmīga');
    }

    /**
     * Izraksta lietotāju no sistēmas.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidē sesiju un atjauno CSRF tokenu, lai novērstu atkārtotu izmantošanu.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Session::forget(['user_id', 'user_name']);

        return redirect('/Login')->with('success', 'Jūs esat atvienots');
    }

}
