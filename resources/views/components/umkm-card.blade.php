@props(['umkm', 'distanceKm' => null])

<a
    href="{{ route('umkm.show', $umkm->slug) }}"
    class="group flex items-center gap-3 rounded-2xl bg-white p-3 shadow-soft transition hover:-translate-y-0.5 hover:shadow-soft-hover"
>
    <div class="relative h-16 w-16 shrink-0 overflow-hidden rounded-xl bg-brand-50">
        @if ($umkm->logo_path)
            <img src="{{ Storage::url($umkm->logo_path) }}" alt="{{ $umkm->name }}" class="h-full w-full object-cover">
        @else
            <div class="grid h-full w-full place-items-center text-lg font-extrabold text-brand-600">
                {{ Str::of($umkm->name)->substr(0, 1)->upper() }}
            </div>
        @endif
    </div>

    <div class="min-w-0 flex-1">
        <div class="flex items-center gap-2">
            <p class="truncate font-semibold text-neutral-800">{{ $umkm->name }}</p>
            <span class="shrink-0 rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $umkm->is_open ? 'bg-brand-100 text-brand-700' : 'bg-neutral-100 text-neutral-500' }}">
                {{ $umkm->is_open ? 'Buka' : 'Tutup' }}
            </span>
        </div>
        <p class="truncate text-sm text-neutral-500">{{ $umkm->region->kelurahan }}</p>
        <div class="mt-1 flex items-center gap-2 text-xs text-neutral-400">
            @if ($umkm->relationLoaded('products'))
                <span>{{ $umkm->products->count() }} produk</span>
            @endif
            @if ($distanceKm !== null)
                <span aria-hidden="true">&middot;</span>
                <span>{{ $distanceKm < 1 ? round($distanceKm * 1000).' m' : round($distanceKm, 1).' km' }} dari kamu</span>
            @endif
        </div>
    </div>

    <span class="shrink-0 text-neutral-300 group-hover:text-brand-600">
        @svg('heroicon-o-chevron-right', 'h-5 w-5')
    </span>
</a>
