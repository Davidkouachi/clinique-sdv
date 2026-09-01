<?php

namespace App\Http\Controllers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\User;


class authController extends Controller
{
    public function login()
    {
        if (Auth::User()) {
            return redirect()->route('index_accueil');
        }

        // // Réinitialiser les api_tokens des utilisateurs qui n'ont pas de session active
        // User::whereNotNull('api_token')->each(function ($user) {
        //     if (!session()->has('user_' . $user->id)) {
        //         $user->update(['api_token' => null]);
        //     }
        // });

        return view('auth.login');
    }

    public function deconnecter(Request $request)
    {
        $user = Auth::User();

        if ($user) {
            $user->update(['api_token' => null]); // Réinitialiser le token
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function mdp_oublie_email()
    {
        return view('auth.mdp_email');
    }

    public function trait_login(Request $request)
    {
        $login = $request->input('login');
        $password = $request->input('password');

        // $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'login' : 'tel';

        $user = User::where('login', $login)->first();

        if (Auth::attempt(['login' => $login, 'password' => $password])) {

            $token = Str::random(60);
            $user->api_token = hash('sha256', $token);
            $user->save();

            // Récupérer le profil utilisateur
            $profil = DB::table('profile')->where('idprofile', '=', $user->user_profil_id)->first();

            if ($profil) {
                session()->put('user_role', $profil->libprofile);
            }

            return response()->json([
                'success' => true,
                'token' => $token,
                'login' => Auth::User()->login,
            ]);
        }

        return response()->json(['error' => true, 'login' => $login,'password' => $password]);
    }

    public function refresh_csrf()
    {
        $token = csrf_token();
        return response()->json(['token' => $token]);
    }
}
