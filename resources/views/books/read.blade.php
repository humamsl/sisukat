@extends('layouts.app')

@section('title', 'Baca: '.$book->title)

@section('content')
<section class="bg-secondary">
    <div class="mx-auto max-w-5xl px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ route('books.show', $book) }}" class="inline-flex items-center gap-1.5 text-sm text-white/70 hover:text-white">
            <x-lucide-chevron-left class="h-4 w-4" /> Kembali ke detail buku
        </a>
        <h1 class="mt-1 truncate text-lg font-semibold text-white">{{ $book->title }}</h1>
    </div>
</section>

<div data-pdf-viewer data-pdf-url="{{ route('books.file', $book) }}" x-data="{ fullscreen: false }" class="bg-ink/5">
    <div class="sticky top-16 z-20 border-b border-ink/10 bg-white">
        <div class="mx-auto flex max-w-5xl flex-wrap items-center justify-between gap-3 px-4 py-3 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2">
                <button type="button" onclick="this.closest('[data-pdf-viewer]').dispatchEvent(new CustomEvent('pdf-prev'))"
                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-ink/15 hover:bg-ink/5" aria-label="Halaman sebelumnya">
                    <x-lucide-chevron-left class="h-4 w-4" />
                </button>
                <span data-pdf-page-label class="min-w-[70px] text-center text-sm font-medium text-secondary">- / -</span>
                <button type="button" onclick="this.closest('[data-pdf-viewer]').dispatchEvent(new CustomEvent('pdf-next'))"
                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-ink/15 hover:bg-ink/5" aria-label="Halaman berikutnya">
                    <x-lucide-chevron-right class="h-4 w-4" />
                </button>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" onclick="this.closest('[data-pdf-viewer]').dispatchEvent(new CustomEvent('pdf-zoom-out'))"
                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-ink/15 hover:bg-ink/5" aria-label="Perkecil">
                    <x-lucide-zoom-out class="h-4 w-4" />
                </button>
                <button type="button" onclick="this.closest('[data-pdf-viewer]').dispatchEvent(new CustomEvent('pdf-zoom-in'))"
                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-ink/15 hover:bg-ink/5" aria-label="Perbesar">
                    <x-lucide-zoom-in class="h-4 w-4" />
                </button>
                <button type="button" @click="fullscreen ? document.exitFullscreen() : $el.closest('[data-pdf-viewer]').requestFullscreen(); fullscreen = !fullscreen"
                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-ink/15 hover:bg-ink/5" aria-label="Layar penuh">
                    <x-lucide-maximize x-show="!fullscreen" class="h-4 w-4" />
                    <x-lucide-minimize x-show="fullscreen" x-cloak class="h-4 w-4" />
                </button>
                <a href="{{ route('books.download', $book) }}" class="flex h-9 items-center gap-1.5 rounded-lg bg-primary px-3 text-sm font-medium text-white hover:opacity-90">
                    <x-lucide-download class="h-4 w-4" /> <span class="hidden sm:inline">Download</span>
                </a>
            </div>
        </div>
    </div>

    <div class="mx-auto flex max-w-5xl justify-center overflow-auto px-4 py-8 sm:px-6 lg:px-8" style="min-height: 70vh;">
        <div class="relative">
            <div data-pdf-loading class="flex h-96 w-full items-center justify-center text-ink/40">
                <x-lucide-loader-2 class="h-8 w-8 animate-spin" />
            </div>
            <p data-pdf-error class="hidden rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">Gagal memuat dokumen PDF.</p>
            <canvas data-pdf-canvas class="rounded-lg bg-white shadow-lg"></canvas>
        </div>
    </div>
</div>
@endsection
