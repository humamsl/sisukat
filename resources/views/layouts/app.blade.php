<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SISUKAT') - Sistem Informasi Supervisi Akademik Terpadu</title>
    <meta name="description" content="@yield('meta_description', 'Platform digital yang menyediakan informasi, panduan, buku saku, tutorial, instrumen, dan pengelolaan dokumen untuk mendukung pelaksanaan supervisi akademik.')">
    <link rel="icon" href="data:,">
    <script>
        // Default ke 'light' bila belum ada pilihan tersimpan (bukan 'system') karena
        // dark mode belum diberi styling penuh di semua komponen — lihat requirement #43.
        (function () {
            var stored = localStorage.getItem('sisukat-theme') || 'light';
            var isDark = stored === 'dark' || (stored === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', isDark);
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface text-ink antialiased">
    <x-navbar />

    <main>
        @yield('content')
    </main>

    <x-footer />

    <div x-data="{ show: false, message: '', type: 'success' }"
         x-on:toast.window="show = true; message = $event.detail.message; type = $event.detail.type ?? 'success'; setTimeout(() => show = false, 4000)"
         x-show="show" x-transition x-cloak
         class="fixed bottom-4 right-4 z-[100] max-w-sm rounded-lg px-4 py-3 text-sm font-medium text-white shadow-lg"
         :class="type === 'success' ? 'bg-emerald-600' : (type === 'error' ? 'bg-red-600' : 'bg-amber-500')">
        <span x-text="message"></span>
    </div>

    @if (session('status'))
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: @json(session('status')), type: 'success' } }));
            });
        </script>
    @endif
</body>
</html>
