<header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-ink/10 bg-white px-4 sm:px-6 lg:px-8">
    <div class="flex items-center gap-3">
        <button @click="sidebarOpen = !sidebarOpen" type="button" aria-label="Buka sidebar"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-ink/70 hover:bg-surface lg:hidden">
            <x-lucide-menu class="h-5 w-5" />
        </button>
        <h1 class="text-sm font-semibold text-secondary sm:text-base">@yield('title', 'Dashboard')</h1>
    </div>

    <div class="flex items-center gap-4">
        <a href="{{ route('home') }}" target="_blank" class="hidden items-center gap-1.5 text-sm text-ink/60 hover:text-primary sm:flex">
            <x-lucide-external-link class="h-4 w-4" /> Lihat Website
        </a>

        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open" type="button" class="flex items-center gap-2 rounded-full border border-ink/10 py-1 pl-1 pr-3 text-sm hover:border-ink/20">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary/10 text-primary">
                    <x-lucide-user class="h-3.5 w-3.5" />
                </span>
                <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                <x-lucide-chevron-down class="h-3.5 w-3.5 text-ink/50" />
            </button>
            <div x-show="open" x-transition x-cloak class="absolute right-0 mt-2 w-52 rounded-lg bg-white py-1 text-sm shadow-xl ring-1 ring-ink/5">
                <div class="border-b border-ink/5 px-3 py-2">
                    <p class="font-medium text-secondary">{{ auth()->user()->name }}</p>
                    <p class="text-xs uppercase tracking-wide text-primary">{{ auth()->user()->role }}</p>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2 px-3 py-2 text-left text-red-600 hover:bg-red-50">
                        <x-lucide-log-out class="h-4 w-4" /> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
