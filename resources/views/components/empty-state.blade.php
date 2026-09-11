@props(['icon' => 'inbox', 'title' => 'Belum ada data', 'description' => null])

<div class="flex flex-col items-center rounded-2xl border border-dashed border-ink/15 px-6 py-14 text-center">
    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-ink/5 text-ink/40">
        <x-dynamic-component :component="'lucide-'.$icon" class="h-6 w-6" />
    </span>
    <p class="mt-4 font-semibold text-secondary">{{ $title }}</p>
    @if ($description)
        <p class="mt-1 max-w-sm text-sm text-ink/50">{{ $description }}</p>
    @endif
    @isset($action)
        <div class="mt-5">{{ $action }}</div>
    @endisset
</div>
