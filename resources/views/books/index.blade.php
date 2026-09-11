@extends('layouts.app')

@section('title', 'Buku Saku Digital')

@section('content')
<section class="bg-secondary py-12 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-extrabold sm:text-3xl">Buku Saku Digital</h1>
        <p class="mt-2 max-w-2xl text-white/70">Kumpulan buku dan panduan digital seputar supervisi akademik yang dapat dibaca dan diunduh secara online.</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <form method="GET" class="mb-8 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau penulis..."
               class="min-w-[240px] flex-1 rounded-lg border border-ink/15 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
        <select name="category" class="rounded-lg border border-ink/15 px-4 py-2.5 text-sm">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <select name="year" class="rounded-lg border border-ink/15 px-4 py-2.5 text-sm">
            <option value="">Semua Tahun</option>
            @foreach ($years as $year)
                <option value="{{ $year }}" @selected(request('year') == $year)>{{ $year }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white">Cari</button>
    </form>

    @if ($books->isEmpty())
        <x-empty-state icon="book-open" title="Belum ada buku yang tersedia" description="Coba ubah kata kunci pencarian atau filter yang digunakan." />
    @else
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($books as $book)
                <a href="{{ route('books.show', $book) }}" class="group flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-ink/5 transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex h-48 items-center justify-center bg-primary/5">
                        @if ($book->cover)
                            <img src="{{ route('books.cover', $book) }}" alt="Cover {{ $book->title }}" class="h-full w-full object-cover">
                        @else
                            <x-lucide-book-open class="h-12 w-12 text-primary/40" />
                        @endif
                    </div>
                    <div class="flex flex-1 flex-col p-5">
                        <p class="text-xs font-medium text-primary">{{ $book->category?->name ?? 'Umum' }}</p>
                        <h3 class="mt-1 font-semibold text-secondary group-hover:text-primary">{{ $book->title }}</h3>
                        <p class="mt-1 text-sm text-ink/50">{{ $book->author }} &middot; {{ $book->year }}</p>
                        <p class="mt-2 line-clamp-2 text-sm text-ink/60">{{ $book->description }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">{{ $books->links() }}</div>
    @endif
</section>
@endsection
