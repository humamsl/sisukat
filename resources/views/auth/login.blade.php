<x-auth.layout title="Login">
    <h1 class="mb-1 text-lg font-semibold">Masuk ke SISUKAT</h1>
    <p class="mb-6 text-sm text-ink/60">Masuk untuk mengakses informasi, Buku Panduan, tutorial, dan instrumen supervisi akademik.</p>

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

    <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
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

    <p class="mt-5 text-center text-sm text-ink/60">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-medium text-primary hover:underline">Daftar di sini</a>
    </p>
</x-auth.layout>
