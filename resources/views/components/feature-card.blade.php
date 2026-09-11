@props(['icon', 'title', 'href' => null])

<a href="{{ $href ?? '#' }}" class="group flex flex-col rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5 transition hover:-translate-y-1 hover:shadow-lg">
    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10 text-primary transition group-hover:bg-gradient-to-br group-hover:from-primary group-hover:to-accent group-hover:text-white">
        <x-dynamic-component :component="'lucide-'.$icon" class="h-5 w-5" />
    </span>
    <h3 class="mt-4 font-semibold">{{ $title }}</h3>
    <p class="mt-1 text-sm text-ink/60">{{ $slot }}</p>
    <span class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-primary opacity-0 transition group-hover:opacity-100">
        Selengkapnya <x-lucide-arrow-right class="h-3.5 w-3.5" />
    </span>
</a>
