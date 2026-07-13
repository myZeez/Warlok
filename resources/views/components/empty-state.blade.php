@props(['icon' => 'heroicon-o-inbox', 'title' => 'Belum ada data', 'description' => null])

<div class="flex flex-col items-center gap-3 rounded-3xl border border-dashed border-neutral-300 bg-white px-6 py-12 text-center">
    <span class="grid h-16 w-16 place-items-center rounded-full bg-brand-50 text-brand-300">
        @svg($icon, 'h-8 w-8')
    </span>
    <p class="font-bold text-neutral-700">{{ $title }}</p>
    @if ($description)
        <p class="max-w-xs text-sm text-neutral-500">{{ $description }}</p>
    @endif
    {{ $slot ?? '' }}
</div>
