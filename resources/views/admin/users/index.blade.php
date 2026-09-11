@extends('layouts.admin')

@section('title', 'Pengguna Admin')

@section('content')
<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <h2 class="text-lg font-semibold text-secondary">Daftar Admin</h2>
    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
        <x-lucide-plus class="h-4 w-4" /> Tambah Admin
    </a>
</div>

@if ($users->isEmpty())
    <x-empty-state icon="users" title="Belum ada admin" />
@else
    <div class="overflow-x-auto rounded-2xl bg-white shadow-sm ring-1 ring-ink/5">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-ink/5 text-xs uppercase text-ink/40">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Login Terakhir</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/5">
                @foreach ($users as $user)
                    <tr>
                        <td class="px-4 py-3 font-medium text-secondary">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-ink/60">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full bg-primary/10 px-2.5 py-1 text-xs font-medium uppercase text-primary">{{ $user->role }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $user->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-ink/5 text-ink/50' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-ink/60">{{ $user->last_login_at?->translatedFormat('d M Y H:i') ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-ink/50 hover:text-primary" title="Edit"><x-lucide-pencil class="h-4 w-4" /></a>
                                @if ($user->isNot(auth()->user()))
                                    <x-confirm-delete :action="route('admin.users.destroy', $user)" :label="'admin \"'.$user->name.'\"'" />
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $users->links() }}</div>
@endif
@endsection
