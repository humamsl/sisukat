@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
{{-- Hero --}}
<section class="relative overflow-hidden bg-secondary text-white">
    <div class="mx-auto grid max-w-7xl gap-12 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:items-center lg:py-24 lg:px-8">
        <div data-aos>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-accent">
                <span class="h-px w-8 bg-accent"></span>
                Sistem Informasi Supervisi Akademik Terpadu
            </div>

            <h1 class="mt-4 text-3xl font-extrabold leading-tight sm:text-4xl lg:text-5xl">
                Transformasi Supervisi Akademik Menjadi Lebih Terpadu dan Digital
            </h1>

            <p class="mt-5 max-w-xl text-white/70">
                Platform digital yang menyediakan informasi, panduan, buku saku, tutorial, instrumen, dan pengelolaan dokumen untuk mendukung pelaksanaan supervisi akademik secara efektif dan terstruktur.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('pages.pendahuluan') }}"
                   class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-primary to-accent px-6 py-3 text-sm font-semibold transition hover:opacity-90">
                    Pelajari SISUKAT <x-lucide-arrow-right class="h-4 w-4" />
                </a>
                <a href="{{ route('instruments.index') }}"
                   class="inline-flex items-center gap-2 rounded-full border border-white/20 px-6 py-3 text-sm font-semibold transition hover:border-white/40 hover:bg-white/5">
                    <x-lucide-download class="h-4 w-4" /> Download Instrumen
                </a>
            </div>

            <div class="mt-10 grid grid-cols-2 gap-4 sm:grid-cols-4">
                @foreach ([
                    ['icon' => 'shield-check', 'label' => 'Aman & Terpercaya'],
                    ['icon' => 'cloud', 'label' => 'Akses Mudah'],
                    ['icon' => 'zap', 'label' => 'Proses Cepat'],
                    ['icon' => 'users', 'label' => 'Terintegrasi Penuh'],
                ] as $badge)
                    <div class="flex flex-col items-start gap-2">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-accent">
                            <x-dynamic-component :component="'lucide-'.$badge['icon']" class="h-5 w-5" />
                        </span>
                        <span class="text-xs text-white/70">{{ $badge['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-center lg:justify-end">
            <x-illustrations.dashboard-mockup />
        </div>
    </div>
</section>

{{-- Fitur Utama --}}
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-2xl text-center">
        <h2 class="text-2xl font-bold text-secondary sm:text-3xl">Fitur Utama SISUKAT</h2>
        <p class="mt-3 text-ink/60">Semua yang Anda butuhkan untuk pelaksanaan supervisi akademik, terpusat dalam satu platform.</p>
    </div>

    <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <x-feature-card icon="info" title="Informasi Supervisi" :href="route('pages.pendahuluan')">
            Informasi lengkap mengenai dasar, tujuan, dan prinsip supervisi akademik.
        </x-feature-card>
        <x-feature-card icon="compass" title="Panduan Penggunaan" :href="route('pages.petunjuk')">
            Petunjuk penggunaan SISUKAT secara mudah dan sistematis.
        </x-feature-card>
        <x-feature-card icon="book-open" title="Buku Saku Digital" :href="route('books.index')">
            Buku saku digital yang dapat dibaca dan diunduh secara online.
        </x-feature-card>
        <x-feature-card icon="video" title="Tutorial" :href="route('tutorials.index')">
            Tutorial dan panduan visual mengenai proses supervisi akademik.
        </x-feature-card>
        <x-feature-card icon="clipboard-list" title="Instrumen Supervisi" :href="route('instruments.index')">
            Dokumen instrumen supervisi yang dapat diunduh sesuai kebutuhan.
        </x-feature-card>
        <x-feature-card icon="upload-cloud" title="Upload Dokumen" :href="route('upload.create')">
            Fasilitas untuk mengunggah dokumen terkait supervisi akademik.
        </x-feature-card>
    </div>
</section>

{{-- Kenali SISUKAT --}}
<section class="bg-white py-16">
    <div class="mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:items-start lg:px-8">
        <div>
            <h2 class="text-2xl font-bold text-secondary sm:text-3xl">Kenali SISUKAT</h2>
            @if ($about)
                <div class="prose-sisukat mt-4 [&>h3:first-child]:mt-0">
                    {!! $about->content !!}
                </div>
            @endif
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            @foreach ([
                ['icon' => 'info', 'title' => 'Apa itu?', 'text' => 'Platform digital pemusat informasi supervisi akademik.'],
                ['icon' => 'lightbulb', 'title' => 'Mengapa dibuat?', 'text' => 'Menjawab kebutuhan transformasi digital supervisi.'],
                ['icon' => 'users', 'title' => 'Siapa penggunanya?', 'text' => 'Guru, kepala sekolah, dan pengawas sekolah.'],
                ['icon' => 'sparkles', 'title' => 'Apa manfaatnya?', 'text' => 'Proses supervisi lebih efektif, transparan, terstruktur.'],
            ] as $point)
                <div class="rounded-2xl bg-surface p-5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <x-dynamic-component :component="'lucide-'.$point['icon']" class="h-4 w-4" />
                    </span>
                    <h3 class="mt-3 text-sm font-semibold text-secondary">{{ $point['title'] }}</h3>
                    <p class="mt-1 text-xs text-ink/60">{{ $point['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
