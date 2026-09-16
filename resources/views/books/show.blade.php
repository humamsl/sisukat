@extends('layouts.app')

@section('title', $book->title)

@section('content')
<section class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
    <x-breadcrumb :items="['Home' => route('home'), 'Buku Panduan' => route('books.index'), $book->title => null]" />

    <div class="grid gap-8 lg:grid-cols-[280px_1fr]">
        <div>
            <div class="flex aspect-[3/4] items-center justify-center overflow-hidden rounded-2xl bg-primary/5 ring-1 ring-ink/5">
                @if ($book->cover)
                    <img src="{{ route('books.cover', $book) }}" alt="Cover {{ $book->title }}" class="h-full w-full object-cover">
                @else
                    <x-lucide-book-open class="h-16 w-16 text-primary/40" />
                @endif
            </div>

            <div class="mt-4 space-y-2">
                @if ($book->read_url)
                    <a href="{{ $book->read_url }}" target="_blank" rel="noopener" class="flex w-full items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90">
                        <x-lucide-book-open-text class="h-4 w-4" /> Baca Online
                    </a>
                @elseif ($book->file)
                    <a href="{{ route('books.read', $book) }}" class="flex w-full items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90">
                        <x-lucide-book-open-text class="h-4 w-4" /> Baca Online
                    </a>
                @endif

                @if ($book->file)
                    <a href="{{ route('books.download', $book) }}" class="flex w-full items-center justify-center gap-2 rounded-lg border border-ink/15 px-4 py-2.5 text-sm font-semibold text-secondary hover:bg-ink/5">
                        <x-lucide-download class="h-4 w-4" /> Download
                    </a>
                @endif

                @if (! $book->read_url && ! $book->file)
                    <p class="rounded-lg bg-ink/5 px-4 py-2.5 text-center text-sm text-ink/50">File belum tersedia.</p>
                @endif
            </div>
        </div>

        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-primary">{{ $book->category?->name ?? 'Umum' }}</p>
            <h1 class="mt-1 text-2xl font-extrabold text-secondary sm:text-3xl">{{ $book->title }}</h1>

            <div class="mt-3 flex flex-wrap gap-4 text-sm text-ink/50">
                @if ($book->author)
                    <span class="flex items-center gap-1.5"><x-lucide-user class="h-4 w-4" /> {{ $book->author }}</span>
                @endif
                @if ($book->year)
                    <span class="flex items-center gap-1.5"><x-lucide-calendar class="h-4 w-4" /> {{ $book->year }}</span>
                @endif
                @if ($book->pages_count)
                    <span class="flex items-center gap-1.5"><x-lucide-file-text class="h-4 w-4" /> {{ $book->pages_count }} halaman</span>
                @endif
                <span class="flex items-center gap-1.5"><x-lucide-download class="h-4 w-4" /> {{ $book->download_count }}x diunduh</span>
            </div>

            <div class="prose-sisukat mt-6">
                <p>{{ $book->description }}</p>
            </div>
        </div>
    </div>
</section>
@endsection
