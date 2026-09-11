<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $code }} - SISUKAT</title>
    <link rel="icon" href="data:,">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-secondary text-white antialiased">
    <div class="flex min-h-screen flex-col items-center justify-center px-4 text-center">
        <a href="/" class="mb-8 inline-flex items-center gap-2 text-xl font-bold tracking-tight">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-primary to-accent text-sm">SK</span>
            <span>SISU<span class="text-primary-light">KAT</span></span>
        </a>

        <div class="w-full max-w-md rounded-2xl bg-white p-8 text-ink shadow-xl">
            <p class="text-5xl font-extrabold text-primary">{{ $code }}</p>
            <h1 class="mt-3 text-lg font-semibold text-secondary">{{ $title }}</h1>
            <p class="mt-2 text-sm text-ink/60">{{ $message }}</p>

            <a href="/" class="mt-6 inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:opacity-90">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
