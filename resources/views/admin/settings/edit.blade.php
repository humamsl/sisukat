@extends('layouts.admin')

@section('title', 'Pengaturan Website')

@section('content')
<h2 class="mb-5 text-lg font-semibold text-secondary">Pengaturan Website</h2>

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="max-w-3xl space-y-8">
    @csrf
    @method('PUT')

    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5">
        <h3 class="mb-4 font-semibold text-secondary">Profil Website</h3>
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-medium text-secondary">Nama Website</label>
                <input name="site_name" type="text" value="{{ old('site_name', $settings['site_name'] ?? '') }}" required
                       class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
                @error('site_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-medium text-secondary">Tagline</label>
                <input name="site_tagline" type="text" value="{{ old('site_tagline', $settings['site_tagline'] ?? '') }}"
                       class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
            </div>
            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-medium text-secondary">Deskripsi</label>
                <textarea name="site_description" rows="3" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                <p class="mt-1 text-xs text-ink/40">Ditampilkan juga sebagai deskripsi singkat pada section "Kenali SISUKAT" di Beranda.</p>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-secondary">Logo</label>
                @if (! empty($settings['logo']))
                    <img src="{{ asset('storage/'.$settings['logo']) }}" alt="Logo" class="mb-2 h-12 rounded bg-secondary p-1">
                @endif
                <input name="logo" type="file" accept="image/png,image/jpeg,image/webp,image/svg+xml" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-primary/10 file:px-3 file:py-1.5 file:text-primary">
                <p class="mt-1 text-xs text-ink/40">PNG/JPG/WEBP/SVG, maks {{ number_format(config('sisukat.uploads.logo_max_kb') / 1024, 1) }} MB. Tampil di navbar &amp;</p>
                @error('logo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-secondary">Favicon</label>
                @if (! empty($settings['favicon']))
                    <img src="{{ asset('storage/'.$settings['favicon']) }}" alt="Favicon" class="mb-2 h-8 w-8 rounded">
                @endif
                <input name="favicon" type="file" accept="image/x-icon,image/png,image/jpeg,.ico" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-primary/10 file:px-3 file:py-1.5 file:text-primary">
                <p class="mt-1 text-xs text-ink/40">ICO/PNG/JPG, maks {{ number_format(config('sisukat.uploads.favicon_max_kb') / 1024, 1) }} MB.</p>
                @error('favicon') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5">
        <h3 class="mb-1 font-semibold text-secondary">Tampilan Beranda</h3>
        <p class="mb-4 text-xs text-ink/40">Running text dan latar hero pada halaman Beranda.</p>

        <div class="space-y-4">
            <div>
                <label class="mb-1 block text-sm font-medium text-secondary">Running Text</label>
                <input name="running_text" type="text" value="{{ old('running_text', $settings['running_text'] ?? '') }}"
                       placeholder="cth. Selamat datang di SISUKAT — pusat informasi supervisi akademik"
                       class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
                <p class="mt-1 text-xs text-ink/40">Teks berjalan di bawah navbar. Kosongkan untuk menyembunyikan.</p>
                @error('running_text') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-secondary">Background Hero</label>
                @if (! empty($settings['hero_background']))
                    <img src="{{ asset('storage/'.$settings['hero_background']) }}" alt="Background hero" class="mb-2 h-24 w-full rounded-lg object-cover ring-1 ring-ink/10">
                @endif
                <input name="hero_background" type="file" accept="image/png,image/jpeg,image/webp" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-primary/10 file:px-3 file:py-1.5 file:text-primary">
                <p class="mt-1 text-xs text-ink/40">PNG/JPG/WEBP, maks {{ number_format(config('sisukat.uploads.hero_background_max_kb') / 1024, 1) }} MB. Kosongkan untuk memakai warna polos bawaan.</p>
                @error('hero_background') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5">
        <h3 class="mb-4 font-semibold text-secondary">Kontak</h3>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-secondary">Email</label>
                <input name="contact_email" type="email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-secondary">Telepon</label>
                <input name="contact_phone" type="text" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
            </div>
            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-medium text-secondary">Alamat</label>
                <input name="contact_address" type="text" value="{{ old('contact_address', $settings['contact_address'] ?? '') }}" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
            </div>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5">
        <h3 class="mb-4 font-semibold text-secondary">Social Media</h3>
        <div class="grid gap-4 sm:grid-cols-3">
            <div>
                <label class="mb-1 block text-sm font-medium text-secondary">Facebook</label>
                <input name="social_facebook" type="url" value="{{ old('social_facebook', $settings['social_facebook'] ?? '') }}" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-secondary">Instagram</label>
                <input name="social_instagram" type="url" value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-secondary">YouTube</label>
                <input name="social_youtube" type="url" value="{{ old('social_youtube', $settings['social_youtube'] ?? '') }}" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
            </div>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5">
        <h3 class="mb-4 font-semibold text-secondary">Warna &amp; Copyright</h3>
        <div class="grid gap-4 sm:grid-cols-3">
            <div>
                <label class="mb-1 block text-sm font-medium text-secondary">Warna Primer</label>
                <input name="color_primary" type="text" value="{{ old('color_primary', $settings['color_primary'] ?? '#2563EB') }}" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-secondary">Warna Sekunder</label>
                <input name="color_secondary" type="text" value="{{ old('color_secondary', $settings['color_secondary'] ?? '#0F172A') }}" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-secondary">Warna Aksen</label>
                <input name="color_accent" type="text" value="{{ old('color_accent', $settings['color_accent'] ?? '#14B8A6') }}" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
            </div>
            <div class="sm:col-span-3">
                <label class="mb-1 block text-sm font-medium text-secondary">Teks Copyright</label>
                <input name="copyright_text" type="text" value="{{ old('copyright_text', $settings['copyright_text'] ?? '') }}" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
            </div>
        </div>
        <p class="mt-3 text-xs text-ink/40">Catatan: warna tema utama situs saat ini diatur lewat CSS variable di <code>resources/css/app.css</code>. Nilai di atas tersimpan untuk referensi/pengembangan lebih lanjut.</p>
    </div>

    <div class="flex justify-end">
        <button type="submit" class="rounded-lg bg-primary px-6 py-2.5 text-sm font-semibold text-white hover:opacity-90">Simpan Pengaturan</button>
    </div>
</form>
@endsection
