<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $request->user()->update([
            'password' => Hash::make($request->validated('password')),
        ]);

        ActivityLogger::log('update', 'Mengubah password sendiri', $request->user());

        return back()->with('status', 'Password berhasil diperbarui.');
    }
}
