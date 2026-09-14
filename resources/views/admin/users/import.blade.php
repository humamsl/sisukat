@extends('layouts.admin')

@section('title', 'Import User')

@section('content')
<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <h2 class="text-lg font-semibold text-secondary">Import Data User</h2>
    <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-ink/60 hover:text-primary">Kembali ke daftar user</a>
</div>

<div class="max-w-2xl space-y-5 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5 sm:p-8">
    <div class="rounded-lg bg-primary/5 px-4 py-3 text-sm text-secondary">
        <p class="font-medium">Format file CSV:</p>
        <p class="mt-1 text-ink/60">Kolom: <code>nama_lengkap</code>, <code>nama_sekolah</code>, <code>email</code>, <code>password</code>, <code>role</code>, <code>status</code>.</p>
        <ul class="mt-2 list-inside list-disc text-ink/60">
            <li><code>password</code> boleh dikosongkan &mdash; sistem akan membuat password acak jika kosong.</li>
            <li><code>role</code>: <code>user</code>, <code>reviewer</code>, <code>admin</code>, atau <code>super_admin</code> (default: <code>user</code>).</li>
            <li><code>status</code>: <code>aktif</code> atau <code>nonaktif</code> (default: aktif).</li>
        </ul>
        <a href="{{ route('admin.users.import.template') }}" class="mt-3 inline-flex items-center gap-1 text-sm font-semibold text-primary hover:underline">
            <x-lucide-download class="h-4 w-4" /> Unduh Template CSV
        </a>
    </div>

    <form method="POST" action="{{ route('admin.users.import.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label for="file" class="mb-1 block text-sm font-medium text-secondary">File CSV</label>
            <input id="file" name="file" type="file" accept=".csv,.txt" required
                   class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-primary/10 file:px-3 file:py-1.5 file:text-primary">
            @error('file') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            <p class="mt-1 text-xs text-ink/40">Maksimal 2 MB.</p>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.users.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-ink/60 hover:bg-ink/5">Batal</a>
            <button type="submit" class="rounded-lg bg-primary px-5 py-2 text-sm font-semibold text-white hover:opacity-90">Import Sekarang</button>
        </div>
    </form>
</div>
@endsection
