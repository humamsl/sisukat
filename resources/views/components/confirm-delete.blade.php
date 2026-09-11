@props(['action', 'label' => 'item ini'])

<div x-data="{ open: false }" class="inline">
    <button type="button" @click="open = true" title="Hapus" class="text-red-600 hover:text-red-700">
        <x-lucide-trash-2 class="h-4 w-4" />
    </button>

    <div x-show="open" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" @click="open = false"></div>
        <div x-show="open" x-transition class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
            <h3 class="text-base font-semibold text-secondary">Konfirmasi Hapus</h3>
            <p class="mt-2 text-sm text-ink/60">Anda yakin ingin menghapus {{ $label }}? Tindakan ini tidak dapat dibatalkan.</p>
            <form method="POST" action="{{ $action }}" class="mt-5 flex justify-end gap-3">
                @csrf
                @method('DELETE')
                <button type="button" @click="open = false" class="rounded-lg px-4 py-2 text-sm font-medium text-ink/60 hover:bg-ink/5">Batal</button>
                <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Hapus</button>
            </form>
        </div>
    </div>
</div>
