<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - Admin SISUKAT</title>
    @php($favicon = \App\Models\Setting::get('favicon'))
    <link rel="icon" href="{{ $favicon ? asset('storage/'.$favicon) : 'data:,' }}">
    <script>
        (function () {
            var stored = localStorage.getItem('sisukat-theme') || 'light';
            var isDark = stored === 'dark' || (stored === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', isDark);
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface text-ink antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        <x-admin.sidebar />

        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-black/50 lg:hidden"></div>

        <div class="flex min-h-screen w-full flex-1 flex-col lg:pl-64">
            <x-admin.topbar />

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                @if (session('status'))
                    <div class="mb-6 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
