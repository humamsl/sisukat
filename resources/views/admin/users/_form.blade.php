@csrf
@isset($user)
    @method('PUT')
@endisset

<div class="max-w-lg space-y-5">
    <div>
        <label for="name" class="mb-1 block text-sm font-medium text-secondary">Nama</label>
        <input id="name" name="name" type="text" value="{{ old('name', $user->name ?? '') }}" required
               class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="email" class="mb-1 block text-sm font-medium text-secondary">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $user->email ?? '') }}" required
               class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="password" class="mb-1 block text-sm font-medium text-secondary">
                Password @isset($user)<span class="text-ink/40">(kosongkan jika tidak diubah)</span>@endisset
            </label>
            <input id="password" name="password" type="password" {{ isset($user) ? '' : 'required' }}
                   class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
            @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="password_confirmation" class="mb-1 block text-sm font-medium text-secondary">Konfirmasi Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password"
                   class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
        </div>
    </div>

    <div>
        <label for="role" class="mb-1 block text-sm font-medium text-secondary">Role</label>
        <select id="role" name="role" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
            <option value="admin" @selected(old('role', $user->role ?? 'admin') === 'admin')>Admin</option>
            <option value="super_admin" @selected(old('role', $user->role ?? '') === 'super_admin')>Super Admin</option>
            <option value="reviewer" @selected(old('role', $user->role ?? '') === 'reviewer')>Reviewer</option>
        </select>
        @error('role') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <label class="flex items-center gap-2 text-sm text-secondary">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active ?? true)) class="rounded border-ink/30 text-primary focus:ring-primary/30">
        Akun aktif
    </label>
</div>

<div class="mt-6 flex justify-end gap-3">
    <a href="{{ route('admin.users.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-ink/60 hover:bg-ink/5">Batal</a>
    <button type="submit" class="rounded-lg bg-primary px-5 py-2 text-sm font-semibold text-white hover:opacity-90">Simpan</button>
</div>
