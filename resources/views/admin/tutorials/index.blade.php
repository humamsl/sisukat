@extends('layouts.admin')

@section('title', 'Tutorial')

@section('content')
<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <h2 class="text-lg font-semibold text-secondary">Daftar Tutorial</h2>
    <a href="{{ route('admin.tutorials.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
        <x-lucide-plus class="h-4 w-4" /> Tambah Tutorial
    </a>
</div>

<form method="GET" class="mb-5 flex flex-wrap gap-3">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul..."
           class="min-w-[220px] flex-1 rounded-lg border border-ink/15 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
    <select name="type" class="rounded-lg border border-ink/15 px-3 py-2 text-sm">
        <option value="">Semua Tipe</option>
        @foreach (['video', 'article', 'pdf', 'image', 'link'] as $type)
            <option value="{{ $type }}" @selected(request('type') === $type)>{{ ucfirst($type) }}</option>
        @endforeach
    </select>
    <button type="submit" class="rounded-lg bg-secondary px-4 py-2 text-sm font-medium text-white">Filter</button>
</form>

@if ($tutorials->isEmpty())
    <x-empty-state icon="video" title="Belum ada tutorial" description="Tambahkan tutorial pertama Anda.">
        <x-slot:action>
            <a href="{{ route('admin.tutorials.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white">Tambah Tutorial</a>
        </x-slot:action>
    </x-empty-state>
@else
    <div class="overflow-x-auto rounded-2xl bg-white shadow-sm ring-1 ring-ink/5">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-ink/5 text-xs uppercase text-ink/40">
                <tr>
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Tipe</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/5">
                @foreach ($tutorials as $tutorial)
                    <tr>
                        <td class="px-4 py-3 font-medium text-secondary">{{ $tutorial->title }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full bg-primary/10 px-2.5 py-1 text-xs font-medium text-primary">{{ ucfirst($tutorial->type) }}</span>
                        </td>
                        <td class="px-4 py-3 text-ink/60">{{ $tutorial->category?->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $tutorial->status === 'published' ? 'bg-emerald-50 text-emerald-700' : 'bg-ink/5 text-ink/50' }}">
                                {{ ucfirst($tutorial->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.tutorials.edit', $tutorial) }}" class="text-ink/50 hover:text-primary" title="Edit"><x-lucide-pencil class="h-4 w-4" /></a>
                                <x-confirm-delete :action="route('admin.tutorials.destroy', $tutorial)" :label="'tutorial \"'.$tutorial->title.'\"'" />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $tutorials->links() }}</div>
@endif
@endsection
