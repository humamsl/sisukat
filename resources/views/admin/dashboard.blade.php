@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <x-admin.stat-card icon="book-open" label="Total Buku Panduan" :value="$stats['books']" color="primary" />
    <x-admin.stat-card icon="video" label="Total Tutorial" :value="$stats['tutorials']" color="accent" />
    <x-admin.stat-card icon="clipboard-list" label="Total Instrumen" :value="$stats['instruments']" color="secondary" />
    <x-admin.stat-card icon="inbox" label="Total Upload" :value="$stats['uploads']" color="primary" />
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-2">
    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-ink/5">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="font-semibold text-secondary">Upload Terbaru</h2>
            <a href="{{ route('admin.uploads.index') }}" class="text-xs font-medium text-primary hover:underline">Lihat semua</a>
        </div>

        @forelse ($recentUploads as $upload)
            <div class="flex items-center justify-between border-b border-ink/5 py-3 last:border-0">
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-secondary">{{ $upload->name }}</p>
                    <p class="text-xs text-ink/50">{{ $upload->document_type }} &middot; {{ $upload->school }}</p>
                </div>
                <span class="ml-3 shrink-0 rounded-full px-2.5 py-1 text-xs font-medium
                    {{ $upload->status === 'pending' ? 'bg-amber-50 text-amber-700' : ($upload->status === 'reviewed' ? 'bg-emerald-50 text-emerald-700' : 'bg-ink/5 text-ink/50') }}">
                    {{ ucfirst($upload->status) }}
                </span>
            </div>
        @empty
            <p class="py-6 text-center text-sm text-ink/40">Belum ada dokumen masuk.</p>
        @endforelse
    </div>

    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-ink/5">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="font-semibold text-secondary">Aktivitas Admin</h2>
            @if (auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.activity-logs.index') }}" class="text-xs font-medium text-primary hover:underline">Lihat semua</a>
            @endif
        </div>

        @forelse ($recentActivity as $log)
            <div class="flex items-start gap-3 border-b border-ink/5 py-3 last:border-0">
                <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
                    <x-lucide-activity class="h-3.5 w-3.5" />
                </span>
                <div class="min-w-0">
                    <p class="text-sm text-secondary">
                        <span class="font-medium">{{ $log->user->name ?? 'Sistem' }}</span>
                        {{ $log->description ?? $log->action }}
                    </p>
                    <p class="text-xs text-ink/40">{{ $log->created_at->diffForHumans() }}</p>
                </div>
            </div>
        @empty
            <p class="py-6 text-center text-sm text-ink/40">Belum ada aktivitas.</p>
        @endforelse
    </div>
</div>
@endsection
