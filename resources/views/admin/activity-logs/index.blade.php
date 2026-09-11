@extends('layouts.admin')

@section('title', 'Log Aktivitas')

@section('content')
<h2 class="mb-5 text-lg font-semibold text-secondary">Log Aktivitas Admin</h2>

<form method="GET" class="mb-5 flex flex-wrap gap-3">
    <select name="action" class="rounded-lg border border-ink/15 px-3 py-2 text-sm">
        <option value="">Semua Aksi</option>
        @foreach (['login', 'logout', 'create', 'update', 'delete', 'upload', 'download'] as $action)
            <option value="{{ $action }}" @selected(request('action') === $action)>{{ ucfirst($action) }}</option>
        @endforeach
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
