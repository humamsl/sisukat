@props(['items'])

<nav class="mb-6 text-sm text-ink/50" aria-label="Breadcrumb">
    @foreach ($items as $label => $url)
        @if ($loop->last)
            <span class="text-ink">{{ $label }}</span>
        @else
            <a href="{{ $url }}" class="hover:text-primary">{{ $label }}</a>
            <span class="mx-1">/</span>
        @endif
    @endforeach
</nav>
