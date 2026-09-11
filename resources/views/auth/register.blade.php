<x-auth.layout title="Daftar Akun">
    <h1 class="mb-1 text-lg font-semibold">Daftar Akun SISUKAT</h1>
    <p class="mb-6 text-sm text-ink/60">Buat akun untuk mengakses informasi, buku saku, tutorial, dan instrumen supervisi akademik.</p>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="mb-1 block text-sm font-medium">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="mb-1 block text-sm font-medium">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            @error('email')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="mb-1 block text-sm font-medium">Password</label>
            <input id="password" type="password" name="password" required
                class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            @error('password')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="mb-1 block text-sm font-medium">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
        </div>

        <button type="submit"
            class="w-full rounded-lg bg-gradient-to-r from-primary to-accent px-4 py-2.5 text-sm font-semibold text-white transition hover:opacity-90">
            Daftar
        </button>
    </form>

    <p class="mt-5 text-center text-sm text-ink/60">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-medium text-primary hover:underline">Masuk di sini</a>
    </p>
</x-auth.layout>
