<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Sejak akun publik (role 'user') ditambahkan, tabel `users` tidak lagi
        // eksklusif untuk staf — di sini pastikan akun aktif DAN berperan staf
        // (super_admin/admin/reviewer). Pembatasan per-aksi berdasarkan role
        // spesifik dilakukan lewat Policy masing-masing modul.
        if (! $user || ! $user->is_active || ! $user->isStaff()) {
            return redirect()
                ->route('home')
                ->with('status', 'Anda tidak memiliki akses ke halaman admin.');
        }

        return $next($request);
    }
}
