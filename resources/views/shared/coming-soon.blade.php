@extends('layouts.app')

@section('title', $title ?? 'Segera Hadir')

@section('content')
<div class="mx-auto flex max-w-2xl flex-col items-center px-4 py-24 text-center sm:px-6">
    <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-primary/10 text-primary">
        <x-lucide-hammer class="h-7 w-7" />
    </span>
    <h1 class="mt-6 text-2xl font-bold text-secondary">{{ $title ?? 'Segera Hadir' }}</h1>
    <p class="mt-2 text-ink/60">Halaman ini sedang dalam pengembangan dan akan tersedia pada fase berikutnya.</p>
    <a href="{{ route('home') }}" class="mt-6 inline-flex items-center gap-2 rounded-full bg-primary px-5 py-2.5 text-sm font-semibold text-white transition hover:opacity-90">
        <x-lucide-arrow-left class="h-4 w-4" /> Kembali ke Beranda
    </a>
</div>
@endsection
