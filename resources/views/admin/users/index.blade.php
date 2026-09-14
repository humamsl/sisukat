@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')
<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <h2 class="text-lg font-semibold text-secondary">Manajemen User</h2>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.users.import') }}" class="inline-flex items-center gap-2 rounded-lg bg-secondary px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
            <x-lucide-upload class="h-4 w-4" /> Import User
        </a>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
            <x-lucide-plus class="h-4 w-4" /> Tambah Akun
        </a>
    </div>
</div>

@if (session('importErrors'))
    <div class="mb-5 rounded-lg bg-amber-50 px-4 py-3 text-sm text-amber-800">
        <p class="font-medium">Rincian baris yang gagal diimpor:</p>
        <ul class="mt-1 list-inside list-disc">
            @foreach (session('importErrors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="GET" class="mb-5 flex flex-wrap gap-3">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau sekolah..."
           class="min-w-[220px] flex-1 rounded-lg border border-ink/15 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
    <select name="role" class="rounded-lg border border-ink/15 px-3 py-2 text-sm">
        <option value="">Semua Role</option>
        <option value="user" @selected(request('role') === 'user')>User</option>
        <option value="reviewer" @selected(request('role') === 'reviewer')>Reviewer</option>
        <option value="admin" @selected(request('role') === 'admin')>Admin</option>
        <option value="super_admin" @selected(request('role') === 'super_admin')>Super Admin</option>
    </select>
    <button type="submit" class="rounded-lg bg-secondary px-4 py-2 text-sm font-medium text-white">Filter</button>
</form>

@if ($users->isEmpty())
    <x-empty-state icon="users" title="Belum ada akun" />
@else
    <div class="overflow-x-auto rounded-2xl bg-white shadow-sm ring-1 ring-ink/5">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-ink/5 text-xs uppercase text-ink/40">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Sekolah</th>
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
                        <td class="px-4 py-3 text-ink/60">{{ $user->school ?? '-' }}</td>
                        <td class="px-4 py-3 text-ink/60">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2.5 py-1 text-xs font-medium uppercase {{ $user->isStaff() ? 'bg-primary/10 text-primary' : 'bg-ink/5 text-ink/50' }}">{{ $user->role }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $user->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-ink/5 text-ink/50' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-ink/60">{{ $user->last_login_at?->translatedFormat('d M Y H:i') ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-ink/50 hover:text-primary" title="Detail / Edit"><x-lucide-pencil class="h-4 w-4" /></a>
                                @if ($user->isNot(auth()->user()))
                                    <x-confirm-delete :action="route('admin.users.destroy', $user)" :label="'akun '.$user->name" />
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
