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
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = Lietotajs::where('lietotajvards', $request->input('name'))
            ->where('parole', $request->input('password'))
            ->first();

        if (! $user) {
            return back()->withErrors(['name' => 'Nekorekts lietotājvārds vai parole'])->withInput();
        }

        // Ja lietotāja e-pasts vēl nav apstiprināts, neielaižam sistēmā,
        // bet nosūtām jaunu kodu un novirzām uz apstiprināšanas formu.
        if (! $user->isEmailVerified()) {
            // Ģenerē vienreiz lietojamu kodu un nosūta to uz lietotāja e-pastu.
            $user->generateAndSendVerificationCode();
            
            return redirect()->route('verify-email.show')
                ->with('info', 'Pārbaudei sūtīts verifikācijas kods uz jūsu e-pastu')
                ->with('user_id', $user->lietotajs_id);
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
            'email' => 'required|email|unique:lietotajs,epasts',
            'password' => 'required|string|min:4',
        ]);

        $user = Lietotajs::create([
            'lietotajvards' => $request->input('name'),
            'epasts' => $request->input('email'),
            'parole' => $request->input('password'),
            'admina_tiesibas' => 0,
        ]);

        // Pēc reģistrācijas konts vēl nav pilnībā aktīvs,
        // tāpēc uzreiz nosūtām e-pasta apstiprināšanas kodu.
        $user->generateAndSendVerificationCode();

        return redirect()->route('verify-email.show')
            ->with('info', 'Reģistrācija veiksmīga! Pārbaudei sūtīts verifikācijas kods uz jūsu e-pastu')
            ->with('user_id', $user->lietotajs_id);
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

    /**
     * Parāda e-pasta verifikācijas formu.
     */
    public function showEmailVerification(Request $request)
    {
        // Lietotāja identifikatoru pieņemam gan no adreses parametra,
        // gan no sesijas, lai forma darbotos pēc pāradresācijas.
        $userId = $request->query('user_id') ?? session('user_id');
        
        if (! $userId) {
            return redirect('/Login')->withErrors(['error' => 'Sesija ir beigusies. Lūdzu, pierakstieties atkārtoti.']);
        }

        $user = Lietotajs::find($userId);
        
        if (! $user) {
            return redirect('/Login')->withErrors(['error' => 'Lietotājs nav atrasts.']);
        }

        return view('verify-email', [
            'user' => $user,
            'message' => session('info'),
        ]);
    }

    /**
     * Apstrādā e-pasta verifikācijas kodu.
     */
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:lietotajs,lietotajs_id',
            'verification_code' => 'required|string|size:6',
        ]);

        $user = Lietotajs::find($request->input('user_id'));

        if (! $user) {
            return back()->withErrors(['error' => 'Lietotājs nav atrasts.']);
        }

        // Salīdzina lietotāja ievadīto kodu ar saglabāto kodu datubāzē.
        if (! $user->verifyEmailCode($request->input('verification_code'))) {
            return back()->withErrors(['verification_code' => 'Kods ir nepareizs vai ir beidzies tā derīgums.'])->withInput();
        }

        // Pēc veiksmīgas apstiprināšanas pabeidzam ielogošanos kā parastā plūsmā.
        Auth::login($user);
        $request->session()->regenerate();
        Session::put('user_name', $user->lietotajvards);

        return redirect('/home')->with('success', 'E-pasts ir verifikāts! Jūs esat pieteicies sistēmā.');
    }

    /**
     * Atkārtoti sūta verifikācijas kodu.
     */
    public function resendVerificationCode(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:lietotajs,lietotajs_id',
        ]);

        $user = Lietotajs::find($request->input('user_id'));

        if (! $user) {
            return back()->withErrors(['error' => 'Lietotājs nav atrasts.']);
        }

        // Atkārtotas nosūtīšanas gadījumā vecais kods tiek aizstāts ar jaunu.
        $user->generateAndSendVerificationCode();

        return back()->with('success', 'Verifikācijas kods atkārtoti sūtīts uz jūsu e-pastu.');
    }
}
