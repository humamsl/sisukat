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
    x-data="{ mobileOpen: false, scrolled: false, themeOpen: false }"
    x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 8)"
    :class="scrolled ? 'shadow-lg shadow-black/20' : ''"
    class="sticky top-0 z-50 bg-secondary text-white transition-shadow"
>
    <nav class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-bold tracking-tight">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-primary to-accent text-xs">SK</span>
            <span>SISU<span class="text-primary-light">KAT</span></span>
        </a>

        <div class="hidden items-center gap-6 lg:flex">
            @foreach ($navLinks as $link)
                <a href="{{ route($link['route']) }}"
                   class="text-sm font-medium transition {{ request()->routeIs($link['route']) ? 'text-white' : 'text-white/70 hover:text-white' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>

        <div class="hidden items-center gap-3 lg:flex">
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" type="button" aria-label="Ganti tema tampilan"
                        class="flex h-9 w-9 items-center justify-center rounded-full text-white/70 transition hover:bg-white/10 hover:text-white">
                    <x-lucide-sun class="h-4 w-4" />
                </button>
                <div x-show="open" x-transition x-cloak
                     class="absolute right-0 mt-2 w-36 rounded-lg bg-white py-1 text-sm text-ink shadow-xl">
                    <button type="button" onclick="window.setSisukatTheme('light')" class="flex w-full items-center gap-2 px-3 py-2 hover:bg-surface">Terang</button>
                    <button type="button" onclick="window.setSisukatTheme('dark')" class="flex w-full items-center gap-2 px-3 py-2 hover:bg-surface">Gelap</button>
                    <button type="button" onclick="window.setSisukatTheme('system')" class="flex w-full items-center gap-2 px-3 py-2 hover:bg-surface">Sistem</button>
                </div>
            </div>

            @auth
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" type="button"
                            class="flex items-center gap-2 rounded-full border border-white/20 py-1.5 pl-1.5 pr-3 text-sm transition hover:border-white/40">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white/10">
                            <x-lucide-user class="h-3.5 w-3.5" />
                        </span>
                        {{ Str::words(auth()->user()->name, 1, '') }}
                        <x-lucide-chevron-down class="h-3.5 w-3.5" />
                    </button>
                    <div x-show="open" x-transition x-cloak
                         class="absolute right-0 mt-2 w-48 rounded-lg bg-white py-1 text-sm text-ink shadow-xl">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 hover:bg-surface">
                            <x-lucide-layout-dashboard class="h-4 w-4" /> Dashboard Admin
                        </a>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-2 px-3 py-2 text-left hover:bg-surface">
                                <x-lucide-log-out class="h-4 w-4" /> Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('admin.login') }}"
                   class="rounded-full border border-white/20 px-4 py-1.5 text-sm font-medium transition hover:border-white/40 hover:bg-white/5">
                    Login
                </a>
            @endauth
        </div>

        <button @click="mobileOpen = !mobileOpen" type="button" aria-label="Buka menu"
                class="flex h-10 w-10 items-center justify-center rounded-lg text-white lg:hidden">
            <x-lucide-menu x-show="!mobileOpen" class="h-6 w-6" />
            <x-lucide-x x-show="mobileOpen" x-cloak class="h-6 w-6" />
        </button>
    </nav>

    <div x-show="mobileOpen" x-cloak x-transition class="border-t border-white/10 bg-secondary lg:hidden">
        <div class="space-y-1 px-4 py-3">
            @foreach ($navLinks as $link)
                <a href="{{ route($link['route']) }}"
                   class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs($link['route']) ? 'bg-white/10 text-white' : 'text-white/70' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach

            <div class="mt-2 border-t border-white/10 pt-3">
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-white/70">Dashboard Admin</a>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="block w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-white/70">Logout</button>
                    </form>
                @else
                    <a href="{{ route('admin.login') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-white/70">Login</a>
                @endauth
            </div>
        </div>
    </div>
</header>
