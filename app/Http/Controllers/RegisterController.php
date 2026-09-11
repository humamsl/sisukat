<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function show(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => User::ROLE_USER,
            'is_active' => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $user->forceFill(['last_login_at' => now()])->save();

        ActivityLogger::log('create', "Pendaftaran akun baru: {$user->name}", $user);

        return redirect()->route('home')->with('status', 'Pendaftaran berhasil. Selamat datang, '.$user->name.'.');
    }
}
