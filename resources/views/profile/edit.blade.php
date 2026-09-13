@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<section class="mx-auto max-w-lg px-4 py-12 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-extrabold text-secondary">Profil Saya</h1>

    <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5">
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between">
                <dt class="text-ink/50">Nama</dt>
                <dd class="font-medium text-secondary">{{ $user->name }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-ink/50">Sekolah</dt>
                <dd class="font-medium text-secondary">{{ $user->school ?? '-' }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-ink/50">Email</dt>
                <dd class="font-medium text-secondary">{{ $user->email }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-ink/50">Peran</dt>
                <dd class="font-medium capitalize text-secondary">{{ str_replace('_', ' ', $user->role) }}</dd>
            </div>
        </dl>
    </div>

    <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5">
        <h2 class="mb-4 font-semibold text-secondary">Ubah Password</h2>

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

        <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="mb-1 block text-sm font-medium text-secondary">Password Saat Ini</label>
                <input id="current_password" type="password" name="current_password" required
                       class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                @error('current_password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="mb-1 block text-sm font-medium text-secondary">Password Baru</label>
                <input id="password" type="password" name="password" required
                       class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-1 block text-sm font-medium text-secondary">Konfirmasi Password Baru</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>

            <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:opacity-90">
                Simpan Password Baru
            </button>
        </form>
    </div>
</section>
@endsection
