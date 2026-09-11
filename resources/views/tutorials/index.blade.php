@extends('layouts.app')

@section('title', 'Tutorial')

@php
    $typeIcons = ['video' => 'play-circle', 'article' => 'file-text', 'pdf' => 'file-text', 'image' => 'image', 'link' => 'link'];
    $typeLabels = ['video' => 'Video', 'article' => 'Artikel', 'pdf' => 'PDF', 'image' => 'Gambar', 'link' => 'Link'];
@endphp

@section('content')
<section class="bg-secondary py-12 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-extrabold sm:text-3xl">Tutorial</h1>
        <p class="mt-2 max-w-2xl text-white/70">Tutorial dan panduan visual mengenai proses supervisi akademik, penggunaan SISUKAT, hingga pengisian instrumen.</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <form method="GET" class="mb-8 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tutorial..."
               class="min-w-[240px] flex-1 rounded-lg border border-ink/15 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
        <select name="category" class="rounded-lg border border-ink/15 px-4 py-2.5 text-sm">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <select name="type" class="rounded-lg border border-ink/15 px-4 py-2.5 text-sm">
            <option value="">Semua Tipe</option>
            @foreach ($typeLabels as $value => $label)
                <option value="{{ $value }}" @selected(request('type') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white">Cari</button>
    </form>

    @if ($tutorials->isEmpty())
        <x-empty-state icon="video" title="Belum ada tutorial" description="Coba ubah kata kunci pencarian atau filter yang digunakan." />
    @else
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($tutorials as $tutorial)
                <a href="{{ route('tutorials.show', $tutorial) }}" class="group flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-ink/5 transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex h-40 items-center justify-center bg-primary/5">
                        @if ($tutorial->thumbnail)
                            <img src="{{ route('tutorials.thumbnail', $tutorial) }}" alt="" class="h-full w-full object-cover">
                        @else
                            <x-dynamic-component :component="'lucide-'.$typeIcons[$tutorial->type]" class="h-10 w-10 text-primary/40" />
                        @endif
                    </div>
                    <div class="flex flex-1 flex-col p-5">
                        <span class="inline-flex w-fit items-center gap-1 rounded-full bg-primary/10 px-2.5 py-1 text-xs font-medium text-primary">
                            <x-dynamic-component :component="'lucide-'.$typeIcons[$tutorial->type]" class="h-3 w-3" /> {{ $typeLabels[$tutorial->type] }}
                        </span>
                        <h3 class="mt-2 font-semibold text-secondary group-hover:text-primary">{{ $tutorial->title }}</h3>
                        <p class="mt-1 line-clamp-2 text-sm text-ink/60">{{ $tutorial->description }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">{{ $tutorials->links() }}</div>
    @endif
</section>
@endsection
