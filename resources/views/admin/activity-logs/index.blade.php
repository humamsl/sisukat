@extends('layouts.admin')

@section('title', 'Log Aktivitas')

@section('content')
<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <h2 class="text-lg font-semibold text-secondary">Log Aktivitas Admin</h2>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.activity-logs.export.excel', request()->query()) }}" class="inline-flex items-center gap-2 rounded-lg border border-ink/15 px-4 py-2 text-sm font-medium text-secondary hover:bg-ink/5">
            <x-lucide-file-spreadsheet class="h-4 w-4" /> Export Excel
        </a>
        <a href="{{ route('admin.activity-logs.export.word', request()->query()) }}" class="inline-flex items-center gap-2 rounded-lg border border-ink/15 px-4 py-2 text-sm font-medium text-secondary hover:bg-ink/5">
            <x-lucide-file-text class="h-4 w-4" /> Export Word
        </a>
    </div>
</div>

<form method="GET" class="mb-5 flex flex-wrap gap-3">
    <select name="action" class="rounded-lg border border-ink/15 px-3 py-2 text-sm">
        <option value="">Semua Aksi</option>
        @foreach (['login', 'logout', 'create', 'update', 'delete', 'upload', 'download'] as $action)
            <option value="{{ $action }}" @selected(request('action') === $action)>{{ ucfirst($action) }}</option>
        @endforeach
    </select>

    <select name="user_filter" class="rounded-lg border border-ink/15 px-3 py-2 text-sm">
        <option value="">Semua User</option>
        <option value="exclude_admin" @selected(request('user_filter') === 'exclude_admin')>Semua User (Tanpa Admin/Staff)</option>
        <optgroup label="Staff / Admin">
            @foreach ($users->whereIn('role', ['super_admin', 'admin', 'reviewer']) as $u)
                <option value="{{ $u->id }}" @selected(request('user_filter') == $u->id)>{{ $u->name }}</option>
            @endforeach
        </optgroup>
        <optgroup label="User Publik">
            @foreach ($users->where('role', 'user') as $u)
                <option value="{{ $u->id }}" @selected(request('user_filter') == $u->id)>{{ $u->name }}</option>
            @endforeach
        </optgroup>
    </select>

    <button type="submit" class="rounded-lg bg-secondary px-4 py-2 text-sm font-medium text-white">Filter</button>
</form>

@if ($logs->isEmpty())
    <x-empty-state icon="history" title="Belum ada aktivitas tercatat" />
@else
    <div class="overflow-x-auto rounded-2xl bg-white shadow-sm ring-1 ring-ink/5">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-ink/5 text-xs uppercase text-ink/40">
                <tr>
                    <th class="px-4 py-3">Waktu</th>
                    <th class="px-4 py-3">User</th>
                    <th class="px-4 py-3">Aksi</th>
                    <th class="px-4 py-3">Deskripsi</th>
                    <th class="px-4 py-3">IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/5">
                @foreach ($logs as $log)
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-ink/60">{{ $log->created_at->translatedFormat('d M Y H:i') }}</td>
                        <td class="px-4 py-3 font-medium text-secondary">{{ $log->user->name ?? 'Sistem' }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full bg-primary/10 px-2.5 py-1 text-xs font-medium capitalize text-primary">{{ $log->action }}</span>
                        </td>
                        <td class="px-4 py-3 text-ink/60">{{ $log->description }}</td>
                        <td class="px-4 py-3 text-ink/40">{{ $log->ip_address }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $logs->links() }}</div>
@endif
@endsection
