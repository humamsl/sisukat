@extends('layouts.admin')

@section('title', 'Edit '.$page->title)

@section('content')
<div class="mx-auto max-w-3xl">
    <h2 class="text-lg font-semibold text-secondary">Edit Konten: {{ $page->title }}</h2>
    <p class="mt-1 text-sm text-ink/50">Konten ini akan tampil pada halaman publik "{{ $page->title }}".</p>

    <form method="POST" action="{{ route('admin.pages.update', $page) }}" class="mt-6 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="title" class="mb-1 block text-sm font-medium text-secondary">Judul Halaman</label>
            <input id="title" name="title" type="text" value="{{ old('title', $page->title) }}"
                   class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            @error('title')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-secondary">Konten</label>
            <div class="rounded-lg border border-ink/15">
                <x-admin.rich-editor name="content" :value="$page->content" />
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.dashboard') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-ink/60 hover:bg-ink/5">Batal</a>
            <button type="submit" class="rounded-lg bg-primary px-5 py-2 text-sm font-semibold text-white hover:opacity-90">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
