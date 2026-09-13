<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Masuk' }} - SISUKAT</title>
    @php($favicon = \App\Models\Setting::get('favicon'))
    <link rel="icon" href="{{ $favicon ? asset('storage/'.$favicon) : 'data:,' }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-secondary text-white antialiased">
    <div class="flex min-h-screen items-center justify-center px-4 py-10">
        <div class="w-full max-w-sm">
            <div class="mb-8 text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-2xl font-bold tracking-tight">
                    <img src="{{ asset('img/logo-sisukat.png') }}" alt="Logo SISUKAT" class="h-9 w-9 object-contain">
                    <span>SISU<span class="text-primary-light">KAT</span></span>
                </a>
                <p class="mt-2 text-sm text-white/60">Sistem Informasi Supervisi Akademik Terpadu</p>
            </div>

            <div class="rounded-2xl bg-white p-8 text-ink shadow-xl">
                {{ $slot }}
            </div>

            <p class="mt-6 text-center text-sm">
                <a href="{{ route('home') }}" class="text-white/60 hover:text-white">&larr; Kembali ke halaman utama</a>
            </p>
        </div>
    </div>
</body>
</html>
