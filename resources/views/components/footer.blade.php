@php
    $footerLinks = [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'Pendahuluan', 'route' => 'pages.pendahuluan'],
        ['label' => 'Petunjuk Penggunaan', 'route' => 'pages.petunjuk'],
        ['label' => 'Buku Saku', 'route' => 'books.index'],
        ['label' => 'Tutorial', 'route' => 'tutorials.index'],
        ['label' => 'Instrumen', 'route' => 'instruments.index'],
        ['label' => 'Upload', 'route' => 'upload.create'],
    ];
@endphp

<footer class="bg-secondary text-white/70">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-2">
                <div class="flex items-center gap-2 text-lg font-bold text-white">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-primary to-accent text-xs">SK</span>
                    <span>SISU<span class="text-primary-light">KAT</span></span>
                </div>
                <p class="mt-3 max-w-sm text-sm">Sistem Informasi Supervisi Akademik Terpadu — platform digital untuk mendukung pelaksanaan supervisi akademik secara efektif dan terstruktur.</p>
            </div>

            <div>
                <h3 class="mb-3 text-sm font-semibold text-white">Navigasi</h3>
                <ul class="space-y-2 text-sm">
                    @auth
                        @foreach ($footerLinks as $link)
                            <li><a href="{{ route($link['route']) }}" class="transition hover:text-white">{{ $link['label'] }}</a></li>
                        @endforeach
                    @else
                        <li><a href="{{ route('home') }}" class="transition hover:text-white">Home</a></li>
                        <li><a href="{{ route('login') }}" class="transition hover:text-white">Login</a></li>
                        <li><a href="{{ route('register') }}" class="transition hover:text-white">Daftar Akun</a></li>
                    @endauth
                </ul>
            </div>

            <div>
                <h3 class="mb-3 text-sm font-semibold text-white">Kontak</h3>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-center gap-2"><x-lucide-mail class="h-4 w-4 shrink-0" /> info@sisukat.local</li>
                    <li class="flex items-center gap-2"><x-lucide-phone class="h-4 w-4 shrink-0" /> 021-0000000</li>
                    <li class="flex items-center gap-2"><x-lucide-map-pin class="h-4 w-4 shrink-0" /> Jl. Pendidikan No. 1, Indonesia</li>
                </ul>
            </div>
        </div>

        <div class="mt-10 border-t border-white/10 pt-6 text-center text-xs text-white/50">
            &copy; {{ now()->year }} SISUKAT. All Rights Reserved.
        </div>
    </div>
</footer>
