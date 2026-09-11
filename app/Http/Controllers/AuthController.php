<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();
        $user->forceFill(['last_login_at' => now()])->save();

        ActivityLogger::log('login', "Login sebagai {$user->name}");

        return redirect()->route('home')->with('status', 'Berhasil masuk. Selamat datang, '.$user->name.'.');
    }

    public function logout(Request $request): RedirectResponse
    {
        ActivityLogger::log('logout', "Logout sebagai {$request->user()?->name}");

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
