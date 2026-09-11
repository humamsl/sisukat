@php
    $navLinks = [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'Pendahuluan', 'route' => 'pages.pendahuluan'],
        ['label' => 'Petunjuk Penggunaan', 'route' => 'pages.petunjuk'],
        ['label' => 'Buku Saku', 'route' => 'books.index'],
        ['label' => 'Tutorial', 'route' => 'tutorials.index'],
        ['label' => 'Instrumen', 'route' => 'instruments.index'],
        ['label' => 'Upload', 'route' => 'upload.create'],
    ];
@endphp

<header
    x-data="{ scrolled: false }"
    x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 8)"
    :class="scrolled ? 'shadow-lg shadow-black/20' : ''"
    class="sticky top-0 z-50 bg-secondary text-white transition-shadow"
>
    <nav class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-2">
            @auth
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" type="button" aria-label="Buka menu navigasi"
                            class="flex h-10 w-10 items-center justify-center rounded-lg text-white/80 transition hover:bg-white/10 hover:text-white">
                        <x-lucide-menu x-show="!open" class="h-5 w-5" />
                        <x-lucide-x x-show="open" x-cloak class="h-5 w-5" />
                    </button>

                    <div x-show="open" x-transition x-cloak
                         class="absolute left-0 z-50 mt-2 w-64 max-w-[calc(100vw-2rem)] overflow-hidden rounded-xl bg-white py-2 text-sm text-ink shadow-xl ring-1 ring-black/5">
                        @foreach ($navLinks as $link)
                            <a href="{{ route($link['route']) }}"
                               class="flex items-center px-4 py-2.5 font-medium transition {{ request()->routeIs($link['route']) ? 'bg-primary/10 text-primary' : 'hover:bg-surface' }}">
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endauth

            <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-bold tracking-tight">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-primary to-accent text-xs">SK</span>
                <span>SISU<span class="text-primary-light">KAT</span></span>
            </a>
        </div>

        @auth
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" type="button"
                        class="flex items-center gap-2 rounded-full border border-white/20 py-1.5 pl-1.5 pr-3 text-sm transition hover:border-white/40">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white/10">
                        <x-lucide-user class="h-3.5 w-3.5" />
                    </span>
                    <span class="hidden sm:inline">{{ Str::words(auth()->user()->name, 1, '') }}</span>
                    <x-lucide-chevron-down class="h-3.5 w-3.5" />
                </button>
                <div x-show="open" x-transition x-cloak
                     class="absolute right-0 z-50 mt-2 w-56 overflow-hidden rounded-xl bg-white py-1 text-sm text-ink shadow-xl ring-1 ring-black/5">
                    <div class="border-b border-ink/5 px-4 py-2.5">
                        <p class="truncate font-medium text-secondary">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-ink/40">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 hover:bg-surface">
                        <x-lucide-user-cog class="h-4 w-4" /> Profil Saya
                    </a>
                    @if (auth()->user()->isStaff())
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 hover:bg-surface">
                            <x-lucide-layout-dashboard class="h-4 w-4" /> Dashboard
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="border-t border-ink/5">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-2 px-4 py-2.5 text-left text-red-600 hover:bg-red-50">
                            <x-lucide-log-out class="h-4 w-4" /> Logout
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="flex items-center gap-2">
                <a href="{{ route('register') }}"
                   class="hidden rounded-full border border-white/20 px-4 py-1.5 text-sm font-medium transition hover:border-white/40 hover:bg-white/5 sm:inline-block">
                    Daftar
                </a>
                <a href="{{ route('login') }}"
                   class="rounded-full bg-gradient-to-r from-primary to-accent px-5 py-1.5 text-sm font-semibold transition hover:opacity-90">
                    Login
                </a>
            </div>
        @endauth
    </nav>
</header>
