@extends('layouts.admin')

@section('title', 'Buku Saku')

@section('content')
<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <h2 class="text-lg font-semibold text-secondary">Daftar Buku Saku</h2>
    <a href="{{ route('admin.books.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
        <x-lucide-plus class="h-4 w-4" /> Tambah Buku
    </a>
</div>

<form method="GET" class="mb-5 flex flex-wrap gap-3">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau penulis..."
           class="min-w-[220px] flex-1 rounded-lg border border-ink/15 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
    <select name="category" class="rounded-lg border border-ink/15 px-3 py-2 text-sm">
        <option value="">Semua Kategori</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>
    <select name="status" class="rounded-lg border border-ink/15 px-3 py-2 text-sm">
        <option value="">Semua Status</option>
        <option value="published" @selected(request('status') === 'published')>Published</option>
        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
    </select>
    <button type="submit" class="rounded-lg bg-secondary px-4 py-2 text-sm font-medium text-white">Filter</button>
</form>

@if ($books->isEmpty())
    <x-empty-state icon="book-open" title="Belum ada buku" description="Tambahkan buku saku digital pertama Anda.">
        <x-slot:action>
            <a href="{{ route('admin.books.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white">Tambah Buku</a>
        </x-slot:action>
    </x-empty-state>
@else
    <div class="overflow-x-auto rounded-2xl bg-white shadow-sm ring-1 ring-ink/5">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-ink/5 text-xs uppercase text-ink/40">
                <tr>
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3">Tahun</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Unduhan</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/5">
                @foreach ($books as $book)
                    <tr>
                        <td class="px-4 py-3">
                            <p class="font-medium text-secondary">{{ $book->title }}</p>
                            <p class="text-xs text-ink/40">{{ $book->author }}</p>
                        </td>
                        <td class="px-4 py-3 text-ink/60">{{ $book->category?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-ink/60">{{ $book->year ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $book->status === 'published' ? 'bg-emerald-50 text-emerald-700' : 'bg-ink/5 text-ink/50' }}">
                                {{ ucfirst($book->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-ink/60">{{ $book->download_count }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.books.edit', $book) }}" class="text-ink/50 hover:text-primary" title="Edit"><x-lucide-pencil class="h-4 w-4" /></a>
                                <x-confirm-delete :action="route('admin.books.destroy', $book)" :label="'buku \"'.$book->title.'\"'" />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $books->links() }}</div>
@endif
@endsection
