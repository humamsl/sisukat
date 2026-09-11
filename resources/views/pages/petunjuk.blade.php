@extends('layouts.app')

@section('title', $page->title)

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-3xl px-4 py-14 sm:px-6 lg:px-8">
        <nav class="mb-6 text-sm text-ink/50">
            <a href="{{ route('home') }}" class="hover:text-primary">Home</a>
            <span class="mx-1">/</span>
            <span class="text-ink">{{ $page->title }}</span>
        </nav>

        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10 text-primary">
            <x-lucide-list-checks class="h-5 w-5" />
        </span>

        <h1 class="mt-4 text-2xl font-extrabold text-secondary sm:text-3xl">{{ $page->title }}</h1>
        <p class="mt-2 text-ink/60">Ikuti langkah-langkah berikut untuk memanfaatkan SISUKAT secara maksimal.</p>

        <div class="prose-sisukat timeline mt-8">
            {!! $page->content !!}
        </div>
    </div>
</section>
@endsection
