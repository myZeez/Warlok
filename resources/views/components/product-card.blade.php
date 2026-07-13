@props(['product', 'distanceKm' => null])

<a
    href="{{ route('products.show', ['umkm' => $product->umkm->slug, 'product' => $product->slug]) }}"
    class="group block overflow-hidden rounded-2xl bg-white shadow-soft transition hover:-translate-y-0.5 hover:shadow-soft-hover"
>
    <div class="relative aspect-square bg-brand-50">
        @if ($product->photo_path)
            <img
                src="{{ Storage::url($product->photo_path) }}"
                alt="{{ $product->name }}"
                class="h-full w-full object-cover"
                loading="lazy"
            >
        @else
            <div class="grid h-full w-full place-items-center bg-gradient-to-br from-brand-50 to-brand-100 text-brand-300">
                @svg($product->category?->icon ?: 'heroicon-o-photo', 'h-11 w-11')
            </div>
        @endif

        @if ($product->stock_status === 'habis')
            <div class="absolute inset-0 grid place-items-center bg-neutral-900/50">
                <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-neutral-800">Stok Habis</span>
            </div>
        @endif

        <button
            type="button"
            x-data
            @click.stop.prevent="$store.favorites.toggle({{ $product->id }})"
            class="absolute right-2 top-2 grid h-9 w-9 place-items-center rounded-full bg-white/90 text-neutral-500 shadow-sm hover:text-rose-500"
            :class="$store.favorites.has({{ $product->id }}) && 'text-rose-500'"
            aria-label="Simpan ke favorit"
        >
            <span x-show="!$store.favorites.has({{ $product->id }})">@svg('heroicon-o-heart', 'h-5 w-5')</span>
            <span x-show="$store.favorites.has({{ $product->id }})" x-cloak>@svg('heroicon-s-heart', 'h-5 w-5')</span>
        </button>
    </div>

    <div class="space-y-1 p-3">
        <p class="line-clamp-2 text-sm font-semibold leading-snug text-neutral-800">{{ $product->name }}</p>
        <p class="truncate text-xs text-neutral-500">{{ $product->umkm->name }}</p>
        <p class="font-bold text-brand-700">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
        @if ($distanceKm !== null)
            <p class="text-[11px] text-neutral-400">
                {{ $distanceKm < 1 ? round($distanceKm * 1000).' m' : round($distanceKm, 1).' km' }} dari kamu
            </p>
        @endif
    </div>
</a>
