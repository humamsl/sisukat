@props(['name', 'value' => ''])

<div>
    <textarea id="{{ $name }}" name="{{ $name }}" class="hidden">{{ old($name, $value) }}</textarea>
    <div data-rich-editor="{{ $name }}" class="rounded-b-lg bg-white" style="min-height: 260px;">{!! old($name, $value) !!}</div>
    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
