@php
    $isSuperAdmin = auth()->user()->isSuperAdmin();

    $groups = [
        [
            'label' => null,
            'items' => [
                ['label' => 'Dashboard', 'icon' => 'layout-dashboard', 'route' => 'admin.dashboard'],
            ],
        ],
        [
            'label' => 'Konten Website',
            'items' => [
                ['label' => 'Pendahuluan', 'icon' => 'file-text', 'route' => 'admin.pages.edit', 'params' => ['page' => 'pendahuluan']],
                ['label' => 'Petunjuk Penggunaan', 'icon' => 'file-text', 'route' => 'admin.pages.edit', 'params' => ['page' => 'petunjuk-penggunaan']],
                ['label' => 'Tentang SISUKAT', 'icon' => 'file-text', 'route' => 'admin.pages.edit', 'params' => ['page' => 'tentang-sisukat']],
            ],
        ],
        [
            'label' => 'Buku Saku',
            'items' => [
                ['label' => 'Daftar Buku', 'icon' => 'book-open', 'route' => 'admin.books.index'],
                ['label' => 'Tambah Buku', 'icon' => 'plus', 'route' => 'admin.books.create'],
            ],
        ],
        [
            'label' => 'Tutorial',
            'items' => [
                ['label' => 'Daftar Tutorial', 'icon' => 'video', 'route' => 'admin.tutorials.index'],
                ['label' => 'Tambah Tutorial', 'icon' => 'plus', 'route' => 'admin.tutorials.create'],
            ],
        ],
        [
            'label' => 'Instrumen',
            'items' => [
                ['label' => 'Daftar Instrumen', 'icon' => 'clipboard-list', 'route' => 'admin.instruments.index'],
                ['label' => 'Tambah Instrumen', 'icon' => 'plus', 'route' => 'admin.instruments.create'],
            ],
        ],
        [
            'label' => 'Upload',
            'items' => [
                ['label' => 'Dokumen Masuk', 'icon' => 'inbox', 'route' => 'admin.uploads.index'],
            ],
        ],
    ];

    if ($isSuperAdmin) {
        $groups[] = [
            'label' => 'Pengguna',
            'items' => [
                ['label' => 'Manajemen User', 'icon' => 'users', 'route' => 'admin.users.index'],
                ['label' => 'Tambah Akun', 'icon' => 'plus', 'route' => 'admin.users.create'],
            ],
        ];
        $groups[] = [
            'label' => 'Pengaturan',
            'items' => [
                ['label' => 'Pengaturan Website', 'icon' => 'settings', 'route' => 'admin.settings.edit'],
                ['label' => 'Log Aktivitas', 'icon' => 'history', 'route' => 'admin.activity-logs.index'],
            ],
        ];
    }
@endphp

<aside x-cloak
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       class="fixed inset-y-0 left-0 z-50 w-64 overflow-y-auto bg-secondary text-white transition-transform lg:translate-x-0">
    <div class="flex h-16 items-center gap-2 px-5">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-primary to-accent text-xs">SK</span>
        <span class="text-lg font-bold">SISU<span class="text-primary-light">KAT</span></span>
    </div>

    <nav class="space-y-6 px-3 pb-10">
        @foreach ($groups as $group)
            <div>
                @if ($group['label'])
                    <p class="px-3 pb-2 text-xs font-semibold uppercase tracking-wider text-white/40">{{ $group['label'] }}</p>
                @endif
                <div class="space-y-1">
                    @foreach ($group['items'] as $item)
                        <a href="{{ route($item['route'], $item['params'] ?? []) }}"
                           class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs($item['route']) ? 'bg-white/10 text-white' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                            <x-dynamic-component :component="'lucide-'.$item['icon']" class="h-4 w-4 shrink-0" />
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left text-sm font-medium text-white/70 hover:bg-white/5 hover:text-white">
                <x-lucide-log-out class="h-4 w-4 shrink-0" /> Logout
            </button>
        </form>
    </nav>
</aside>
