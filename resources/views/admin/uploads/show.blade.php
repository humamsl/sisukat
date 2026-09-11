@extends('layouts.admin')

@section('title', 'Detail Dokumen')

@section('content')
<div class="mx-auto max-w-2xl">
    <a href="{{ route('admin.uploads.index') }}" class="inline-flex items-center gap-1.5 text-sm text-ink/50 hover:text-primary">
        <x-lucide-chevron-left class="h-4 w-4" /> Kembali ke Dokumen Masuk
    </a>

    <div class="mt-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-secondary">{{ $upload->document_type }}</h2>
            <span class="rounded-full px-2.5 py-1 text-xs font-medium
                {{ $upload->status === 'pending' ? 'bg-amber-50 text-amber-700' : ($upload->status === 'reviewed' ? 'bg-emerald-50 text-emerald-700' : 'bg-ink/5 text-ink/50') }}">
                {{ ucfirst($upload->status) }}
            </span>
        </div>

        <dl class="mt-5 grid gap-4 sm:grid-cols-2">
            <div>
                <dt class="text-xs uppercase text-ink/40">Nama</dt>
                <dd class="text-sm text-secondary">{{ $upload->name }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase text-ink/40">Email</dt>
                <dd class="text-sm text-secondary">{{ $upload->email }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase text-ink/40">NIP</dt>
                <dd class="text-sm text-secondary">{{ $upload->identity_number ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase text-ink/40">Jabatan</dt>
                <dd class="text-sm text-secondary">{{ $upload->position }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase text-ink/40">Sekolah</dt>
                <dd class="text-sm text-secondary">{{ $upload->school }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase text-ink/40">Tanggal Kirim</dt>
                <dd class="text-sm text-secondary">{{ $upload->uploaded_at->translatedFormat('d M Y H:i') }}</dd>
            </div>
        </dl>

        @if ($upload->description)
            <div class="mt-5">
                <dt class="text-xs uppercase text-ink/40">Keterangan</dt>
                <dd class="mt-1 text-sm text-secondary">{{ $upload->description }}</dd>
            </div>
        @endif

        <div class="mt-5 flex items-center gap-2 rounded-lg bg-surface px-4 py-3 text-sm">
            <x-lucide-file-check-2 class="h-4 w-4 text-primary" />
            <span class="text-secondary">{{ $upload->original_filename }}</span>
            <span class="text-ink/40">({{ round($upload->file_size / 1024, 1) }} KB)</span>
            <a href="{{ route('admin.uploads.download', $upload) }}" class="ml-auto text-primary hover:underline">Download</a>
        </div>

        <form method="POST" action="{{ route('admin.uploads.status', $upload) }}" class="mt-6 flex items-center gap-3">
            @csrf
            @method('PATCH')
            <label for="status" class="text-sm font-medium text-secondary">Ubah Status:</label>
            <select id="status" name="status" class="rounded-lg border border-ink/15 px-3 py-2 text-sm">
                <option value="pending" @selected($upload->status === 'pending')>Pending</option>
                <option value="reviewed" @selected($upload->status === 'reviewed')>Reviewed</option>
                <option value="archived" @selected($upload->status === 'archived')>Archived</option>
            </select>
            <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:opacity-90">Simpan</button>
        </form>
    </div>
</div>
@endsection
