@extends('layouts.app')

@section('title', $tutorial->title)

@section('content')
<section class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
    <nav class="mb-6 text-sm text-ink/50">
        <a href="{{ route('home') }}" class="hover:text-primary">Home</a>
        <span class="mx-1">/</span>
        <a href="{{ route('tutorials.index') }}" class="hover:text-primary">Tutorial</a>
        <span class="mx-1">/</span>
        <span class="text-ink">{{ $tutorial->title }}</span>
    </nav>

    <p class="text-xs font-medium uppercase tracking-wide text-primary">{{ $tutorial->category?->name ?? ucfirst($tutorial->type) }}</p>
    <h1 class="mt-1 text-2xl font-extrabold text-secondary sm:text-3xl">{{ $tutorial->title }}</h1>
    @if ($tutorial->description)
        <p class="mt-3 text-ink/60">{{ $tutorial->description }}</p>
    @endif

    <div class="mt-8">
        @switch($tutorial->type)
            @case('video')
                @if ($tutorial->embed_url)
                    <div class="aspect-video overflow-hidden rounded-2xl bg-black shadow-lg">
                        <iframe src="{{ $tutorial->embed_url }}" class="h-full w-full" allowfullscreen
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                    </div>
                @else
                    <a href="{{ $tutorial->video_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white">
                        <x-lucide-external-link class="h-4 w-4" /> Buka Video
                    </a>
                @endif
            @break

            @case('article')
                <div class="prose-sisukat">{!! $tutorial->content !!}</div>
            @break

            @case('pdf')
                <div class="overflow-hidden rounded-2xl ring-1 ring-ink/10">
                    <iframe src="{{ route('tutorials.file', $tutorial) }}" class="h-[70vh] w-full"></iframe>
                </div>
            @break

            @case('image')
                <img src="{{ route('tutorials.file', $tutorial) }}" alt="{{ $tutorial->title }}" class="w-full rounded-2xl shadow-lg">
            @break

            @case('link')
                <a href="{{ $tutorial->external_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:opacity-90">
                    <x-lucide-external-link class="h-4 w-4" /> Buka Tautan Eksternal
                </a>
            @break
        @endswitch
    </div>
</section>
@endsection
