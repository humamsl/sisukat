<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SISUKAT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-secondary text-white antialiased">
    <div class="flex min-h-screen items-center justify-center px-4">
        <div class="w-full max-w-sm">
            <div class="mb-8 text-center">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-2xl font-bold tracking-tight">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-primary to-accent text-sm text-white">SK</span>
                    <span>SISU<span class="text-primary-light">KAT</span></span>
                </a>
                <p class="mt-2 text-sm text-white/60">Sistem Informasi Supervisi Akademik Terpadu</p>
            </div>

            <div class="rounded-2xl bg-white p-8 text-ink shadow-xl">
                <h1 class="mb-1 text-lg font-semibold">Masuk ke Dashboard Admin</h1>
                <p class="mb-6 text-sm text-ink/60">Khusus untuk pengelola konten SISUKAT.</p>

                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (session('status'))
                    <div class="mb-4 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="mb-1 block text-sm font-medium">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-1 block text-sm font-medium">Password</label>
                        <input id="password" type="password" name="password" required
                            class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>

                    <label class="flex items-center gap-2 text-sm text-ink/70">
                        <input type="checkbox" name="remember" class="rounded border-ink/30 text-primary focus:ring-primary/30">
                        Ingat saya
                    </label>

                    <button type="submit"
                        class="w-full rounded-lg bg-gradient-to-r from-primary to-accent px-4 py-2.5 text-sm font-semibold text-white transition hover:opacity-90">
                        Masuk
                    </button>
                </form>
            </div>

            <p class="mt-6 text-center text-sm">
                <a href="{{ url('/') }}" class="text-white/60 hover:text-white">&larr; Kembali ke halaman utama</a>
            </p>
        </div>
    </div>
</body>
</html>
