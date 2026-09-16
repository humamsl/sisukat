@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
{{-- Hero --}}
@php($heroBg = \App\Models\Setting::get('hero_background'))
<section class="relative overflow-hidden bg-secondary bg-cover bg-center text-white"
         @if ($heroBg) style="background-image: url('{{ asset('storage/'.$heroBg) }}')" @endif>
    @if ($heroBg)
        <div class="absolute inset-0 bg-secondary/80"></div>
    @endif
    <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:py-24 lg:px-8">
        <div data-aos class="max-w-2xl">
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-accent">
                <span class="h-px w-8 bg-accent"></span>
                Sistem Informasi Supervisi Akademik Terpadu
            </div>

            <h1 class="mt-2 text-4xl font-extrabold leading-tight sm:text-5xl lg:text-6xl">
                Selamat Datang<br>
                di <span style="color: #0048b5;">SISUKAT</span>
            </h1>

            <p class="mt-5 max-w-xl text-base text-white/70 sm:text-lg">
                Platform digital yang menyediakan Informasi, Buku Panduan, Tutorial, Instrumen, dan Pengelolaan dokumen untuk mendukung pelaksanaan supervisi akademik secara efektif dan terstruktur.
            </p>

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
            <h1 class="mt-4 text-3xl font-extrabold leading-tight sm:text-4xl lg:text-5xl" style="margin: 10px;padding: 10px;border: 1px; color: #ffffff00;">   a </h1>
        </div>

        <!--
        <div class="flex justify-center lg:justify-end">
            <x-illustrations.dashboard-mockup />
        </div> -->
    
    </div>
</section>
<!--
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
        <x-feature-card icon="book-open" title="Buku Panduan Digital" :href="route('books.index')">
            Buku Panduan digital yang dapat dibaca dan diunduh secara online.
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
</section> -->

{{-- Kenali SISUKAT --}}
@php($logo = \App\Models\Setting::get('logo'))
<section class="bg-white py-20 sm:py-28">
    <div class="mx-auto mb-14 max-w-4xl px-4 text-center sm:px-6">
        @if ($logo)
            <img src="{{ asset('storage/'.$logo) }}" alt="Logo Sekolah" style="width:200px;height:200px;object-fit:contain;margin:0 auto 12px;display:block;">
        @else
            <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-primary to-accent text-lg font-bold text-white">SK</span>
        @endif

        <h2 class="mt-6 text-2xl font-bold text-secondary sm:text-3xl"></h2>

        @if ($settingDescription = \App\Models\Setting::get('site_description'))
            <p class="mx-auto mt-3 max-w-4xl text-ink/80" style="margin-top: 10px;padding: 10px;border: 1px; font-size: 16px;">{{ $settingDescription }}</p>
        @endif
    </div>

    <div class="mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:items-start lg:px-8">
        <div>
            @if ($about)
                <div class="prose-sisukat [&>h3:first-child]:mt-0">
                    {!! $about->content !!}
                </div>
            @endif
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            @foreach ([
                ['icon' => 'info', 'title' => 'Apa itu?', 'text' => 'Platform digital pemusat informasi supervisi akademik.'],
                ['icon' => 'lightbulb', 'title' => 'Mengapa dibuat?', 'text' => 'Menjawab kebutuhan transformasi digital supervisi.'],
                ['icon' => 'users', 'title' => 'Siapa penggunanya?', 'text' => 'Kepala sekolah, dan pengawas sekolah.'],
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
