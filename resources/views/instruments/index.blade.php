@extends('layouts.app')

@section('title', 'Download Instrumen Supervisi')

@section('content')
<section class="bg-secondary py-12 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-extrabold sm:text-3xl">Download Instrumen Supervisi</h1>
        <p class="mt-2 max-w-2xl text-white/70">Kumpulan dokumen instrumen supervisi akademik yang dapat diunduh untuk mendukung kegiatan supervisi di sekolah.</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <form method="GET" class="mb-8 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari instrumen..."
               class="min-w-[240px] flex-1 rounded-lg border border-ink/15 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
        <select name="category" class="rounded-lg border border-ink/15 px-4 py-2.5 text-sm">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <select name="format" class="rounded-lg border border-ink/15 px-4 py-2.5 text-sm">
            <option value="">Semua Format</option>
            @foreach (['pdf', 'doc', 'docx', 'xls', 'xlsx'] as $format)
                <option value="{{ $format }}" @selected(request('format') === $format)>{{ strtoupper($format) }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white">Cari</button>
    </form>

    @if ($instruments->isEmpty())
        <x-empty-state icon="clipboard-list" title="Belum ada instrumen supervisi" description="Coba ubah kata kunci pencarian atau filter yang digunakan." />
    @else
        <div class="space-y-4">
            @foreach ($instruments as $instrument)
                <div class="flex flex-col gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-ink/5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                            <x-lucide-file-check-2 class="h-5 w-5" />
                        </span>
                        <div>
                            <p class="text-xs font-medium text-primary">{{ $instrument->category?->name ?? 'Umum' }}</p>
                            <h3 class="font-semibold text-secondary">{{ $instrument->title }}</h3>
                            <p class="mt-1 line-clamp-1 text-sm text-ink/60">{{ $instrument->description }}</p>
                            <div class="mt-2 flex flex-wrap gap-3 text-xs text-ink/40">
                                <span class="rounded-full bg-ink/5 px-2 py-0.5 font-medium uppercase">{{ $instrument->file_type }}</span>
                                <span>{{ $instrument->file_size_formatted }}</span>
                                @if ($instrument->year)<span>Tahun {{ $instrument->year }}</span>@endif
                                <span>Diperbarui {{ $instrument->updated_at->translatedFormat('d M Y') }}</span>
                                <span>{{ $instrument->download_count }}x diunduh</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex shrink-0 gap-2">
                        <a href="{{ route('instruments.preview', $instrument) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-ink/15 px-4 py-2 text-sm font-medium text-secondary hover:bg-ink/5">
                            <x-lucide-eye class="h-4 w-4" /> Preview
                        </a>
                        <a href="{{ route('instruments.download', $instrument) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                            <x-lucide-download class="h-4 w-4" /> Download
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">{{ $instruments->links() }}</div>
    @endif
</section>
@endsection
