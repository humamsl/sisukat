@extends('layouts.app')

@section('title', 'Pencarian')

@section('content')
<section class="bg-secondary py-10 text-white">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-extrabold sm:text-3xl">Pencarian</h1>
        <form method="GET" action="{{ route('search.index') }}" class="mt-5 flex gap-2">
            <input type="text" name="q" value="{{ $term }}" autofocus placeholder="Cari buku, tutorial, atau instrumen..."
                   class="flex-1 rounded-lg border-0 px-4 py-2.5 text-sm text-ink focus:outline-none focus:ring-2 focus:ring-accent">
            <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white">Cari</button>
        </form>
    </div>
</section>

<section class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
    @if ($term === '')
        <p class="text-center text-ink/50">Masukkan kata kunci untuk mulai mencari.</p>
    @elseif ($results->isEmpty())
        <x-empty-state icon="search" title="Tidak ada hasil untuk &quot;{{ $term }}&quot;" description="Coba gunakan kata kunci lain." />
    @else
        <p class="mb-5 text-sm text-ink/50">{{ $results->total() }} hasil ditemukan untuk "{{ $term }}"</p>

        <div class="space-y-3">
            @foreach ($results as $result)
                <a href="{{ $result['url'] }}" class="flex items-start gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-ink/5 transition hover:shadow-md">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <x-dynamic-component :component="'lucide-'.$result['icon']" class="h-5 w-5" />
                    </span>
                    <div class="min-w-0">
                        <span class="text-xs font-medium uppercase tracking-wide text-primary">{{ $result['type'] }}</span>
                        <h3 class="font-semibold text-secondary">{{ $result['title'] }}</h3>
                        @if ($result['description'])
                            <p class="mt-1 line-clamp-1 text-sm text-ink/60">{{ $result['description'] }}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">{{ $results->links() }}</div>
    @endif
</section>
@endsection
