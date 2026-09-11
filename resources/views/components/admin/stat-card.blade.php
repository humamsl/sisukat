@props(['icon', 'label', 'value', 'color' => 'primary'])

@php
    $colorClasses = match ($color) {
        'accent' => 'bg-accent/10 text-accent',
        'secondary' => 'bg-secondary/10 text-secondary',
        default => 'bg-primary/10 text-primary',
    };
@endphp

<div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-ink/5">
    <span class="flex h-10 w-10 items-center justify-center rounded-xl {{ $colorClasses }}">
        <x-dynamic-component :component="'lucide-'.$icon" class="h-5 w-5" />
    </span>
    <p class="mt-4 text-2xl font-bold text-secondary">{{ $value }}</p>
    <p class="text-sm text-ink/50">{{ $label }}</p>
</div>
