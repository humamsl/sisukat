@extends('layouts.admin')

@section('title', 'Dokumen Masuk')

@section('content')
<h2 class="mb-5 text-lg font-semibold text-secondary">Dokumen Masuk</h2>

<form method="GET" class="mb-5 flex flex-wrap gap-3">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, sekolah, atau jenis dokumen..."
           class="min-w-[240px] flex-1 rounded-lg border border-ink/15 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
    <select name="status" class="rounded-lg border border-ink/15 px-3 py-2 text-sm">
        <option value="">Semua Status</option>
        <option value="pending" @selected(request('status') === 'pending')>Pending</option>
        <option value="reviewed" @selected(request('status') === 'reviewed')>Reviewed</option>
        <option value="archived" @selected(request('status') === 'archived')>Archived</option>
    </select>
    <button type="submit" class="rounded-lg bg-secondary px-4 py-2 text-sm font-medium text-white">Filter</button>
</form>

@if ($uploads->isEmpty())
    <x-empty-state icon="inbox" title="Belum ada dokumen masuk" description="Dokumen yang dikirim melalui halaman Upload akan muncul di sini." />
@else
    <div class="overflow-x-auto rounded-2xl bg-white shadow-sm ring-1 ring-ink/5">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-ink/5 text-xs uppercase text-ink/40">
                <tr>
                    <th class="px-4 py-3">Pengirim</th>
                    <th class="px-4 py-3">Jenis Dokumen</th>
                    <th class="px-4 py-3">Sekolah</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/5">
                @foreach ($uploads as $upload)
                    <tr>
                        <td class="px-4 py-3">
                            <p class="font-medium text-secondary">{{ $upload->name }}</p>
                            <p class="text-xs text-ink/40">{{ $upload->email }}</p>
                        </td>
                        <td class="px-4 py-3 text-ink/60">{{ $upload->document_type }}</td>
                        <td class="px-4 py-3 text-ink/60">{{ $upload->school }}</td>
                        <td class="px-4 py-3 text-ink/60">{{ $upload->uploaded_at->translatedFormat('d M Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2.5 py-1 text-xs font-medium
                                {{ $upload->status === 'pending' ? 'bg-amber-50 text-amber-700' : ($upload->status === 'reviewed' ? 'bg-emerald-50 text-emerald-700' : 'bg-ink/5 text-ink/50') }}">
                                {{ ucfirst($upload->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.uploads.show', $upload) }}" class="text-ink/50 hover:text-primary" title="Detail"><x-lucide-eye class="h-4 w-4" /></a>
                                <a href="{{ route('admin.uploads.download', $upload) }}" class="text-ink/50 hover:text-primary" title="Download"><x-lucide-download class="h-4 w-4" /></a>
                                <x-confirm-delete :action="route('admin.uploads.destroy', $upload)" :label="'dokumen dari '.$upload->name" />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $uploads->links() }}</div>
@endif
@endsection
