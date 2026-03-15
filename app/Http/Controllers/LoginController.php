<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Lietotajs;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('Login');
    }

    /**
     * Handle form submission and validate the user credentials.
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

        Auth::login($user);
        $request->session()->regenerate();

        // Still keep a small session helper for any non-Auth uses.
        Session::put('user_name', $user->lietotajvards);

        return redirect('/home')->with('success', 'Pieteikšanās veiksmīga');
    }

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        return view('Register');
    }

    /**
     * Handle registration and store a new Lietotajs record.
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
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate session and regenerate CSRF token to prevent reuse.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Session::forget(['user_id', 'user_name']);

        return redirect('/Login')->with('success', 'Jūs esat atvienots');
    }
}
