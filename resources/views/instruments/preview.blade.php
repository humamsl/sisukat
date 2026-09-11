@extends('layouts.app')

@section('title', $instrument->title)

@section('content')
<section class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
    <x-breadcrumb :items="['Home' => route('home'), 'Instrumen' => route('instruments.index'), $instrument->title => null]" />

    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-primary">{{ $instrument->category?->name ?? 'Umum' }}</p>
            <h1 class="mt-1 text-2xl font-extrabold text-secondary sm:text-3xl">{{ $instrument->title }}</h1>
            <div class="mt-3 flex flex-wrap gap-4 text-sm text-ink/50">
                <span class="rounded-full bg-ink/5 px-2.5 py-1 font-medium uppercase">{{ $instrument->file_type }}</span>
                <span class="flex items-center gap-1.5"><x-lucide-file-text class="h-4 w-4" /> {{ $instrument->file_size_formatted }}</span>
                @if ($instrument->year)
                    <span class="flex items-center gap-1.5"><x-lucide-calendar class="h-4 w-4" /> {{ $instrument->year }}</span>
                @endif
                <span class="flex items-center gap-1.5"><x-lucide-download class="h-4 w-4" /> {{ $instrument->download_count }}x diunduh</span>
            </div>
        </div>
        <a href="{{ route('instruments.download', $instrument) }}" class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:opacity-90">
            <x-lucide-download class="h-4 w-4" /> Download
        </a>
    </div>

    @if ($instrument->description)
        <p class="mt-6 text-ink/70">{{ $instrument->description }}</p>
    @endif

    <div class="mt-8">
        @if ($instrument->file_type === 'pdf')
            <div class="overflow-hidden rounded-2xl ring-1 ring-ink/10">
                <iframe src="{{ route('instruments.file', $instrument) }}" class="h-[70vh] w-full"></iframe>
            </div>
        @else
            <div class="flex flex-col items-center rounded-2xl border border-dashed border-ink/15 px-6 py-14 text-center">
                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-ink/5 text-ink/40">
                    <x-lucide-file-spreadsheet class="h-6 w-6" />
                </span>
                <p class="mt-4 font-semibold text-secondary">Pratinjau tidak tersedia untuk format {{ strtoupper($instrument->file_type) }}</p>
                <p class="mt-1 max-w-sm text-sm text-ink/50">Silakan unduh dokumen untuk membukanya di aplikasi Word/Excel Anda.</p>
            </div>
        @endif
    </div>
</section>
@endsection
