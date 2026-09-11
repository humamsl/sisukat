<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Kolom `role` sudah membatasi tabel `users` hanya untuk akun staf
        // (super_admin/admin/reviewer) — di sini cukup pastikan akun masih aktif.
        // Pembatasan per-aksi berdasarkan role dilakukan lewat Policy masing-masing modul.
        if (! $user || ! $user->is_active) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('admin.login')
                ->withErrors(['email' => 'Akun tidak memiliki akses admin atau sudah tidak aktif.']);
        }

        return $next($request);
    }
}
