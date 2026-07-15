@props(['rating' => null, 'count' => null, 'size' => 'h-4 w-4'])

<div {{ $attributes->merge(['class' => 'flex items-center gap-1.5']) }}>
    <div class="flex items-center gap-0.5">
        @for ($i = 1; $i <= 5; $i++)
            @svg(
                $rating !== null && $i <= round($rating) ? 'heroicon-s-star' : 'heroicon-o-star',
                $size.' '.($rating !== null && $i <= round($rating) ? 'text-amber-400' : 'text-neutral-300')
            )
        @endfor
    </div>

    @if ($rating !== null)
        <span class="text-xs font-semibold text-neutral-600">{{ number_format($rating, 1) }}</span>
        @if ($count !== null)
            <span class="text-xs text-neutral-400">({{ $count }})</span>
        @endif
    @else
        <span class="text-xs text-neutral-400">Belum ada ulasan</span>
    @endif
</div>
