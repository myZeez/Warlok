@props([
    'reverse' => false,
    'pauseOnHover' => false,
    'vertical' => false,
    'repeat' => 4,
])

<div
    {{ $attributes->class([
        'group flex overflow-hidden p-2 [--duration:40s] [--gap:1rem] [gap:var(--gap)]',
        'flex-col' => $vertical,
        'flex-row' => ! $vertical,
    ]) }}
    role="marquee"
>
    @for ($i = 0; $i < $repeat; $i++)
        <div
            @class([
                'flex shrink-0 justify-around [gap:var(--gap)]',
                'flex-row animate-marquee' => ! $vertical,
                'flex-col animate-marquee-vertical' => $vertical,
                'group-hover:[animation-play-state:paused]' => $pauseOnHover,
                '[animation-direction:reverse]' => $reverse,
            ])
            aria-hidden="{{ $i > 0 ? 'true' : 'false' }}"
        >
            {{ $slot }}
        </div>
    @endfor
</div>
