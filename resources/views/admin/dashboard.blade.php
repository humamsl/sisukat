<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin - SISUKAT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-surface text-ink antialiased">
    <div class="flex min-h-screen items-center justify-center px-4">
        <div class="w-full max-w-md rounded-2xl bg-white p-8 text-center shadow-xl">
            <p class="text-sm text-ink/60">Selamat datang,</p>
            <h1 class="mt-1 text-xl font-semibold">{{ auth()->user()->name }}</h1>
            <p class="mt-1 text-xs uppercase tracking-wide text-primary">{{ auth()->user()->role }}</p>

            <p class="mt-6 text-sm text-ink/60">
                Dashboard lengkap (statistik, sidebar, topbar) akan dibangun pada Phase 5.
            </p>

            <form method="POST" action="{{ route('admin.logout') }}" class="mt-6">
                @csrf
                <button type="submit" class="w-full rounded-lg bg-secondary px-4 py-2.5 text-sm font-semibold text-white transition hover:opacity-90">
                    Logout
                </button>
            </form>
        </div>
    </div>
</body>
</html>
