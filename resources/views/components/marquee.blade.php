@php($text = \App\Models\Setting::get('running_text'))

@if (! empty($text))
    <div class="overflow-hidden border-y border-black/10 bg-[#D8C9A3] py-2">
        <div class="flex w-max animate-marquee">
            <div class="flex shrink-0 items-center whitespace-nowrap" aria-hidden="false">
                @for ($i = 0; $i < 6; $i++)
                    <span class="mx-8 text-sm font-bold uppercase tracking-wider text-secondary">{{ $text }}</span>
                @endfor
            </div>
            <div class="flex shrink-0 items-center whitespace-nowrap" aria-hidden="true">
                @for ($i = 0; $i < 6; $i++)
                    <span class="mx-8 text-sm font-bold uppercase tracking-wider text-secondary">{{ $text }}</span>
                @endfor
            </div>
        </div>
    </div>
@endif
