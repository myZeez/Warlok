<x-layouts.app :title="$product->name">
    <div class="mx-auto max-w-4xl space-y-5 px-4 py-6">
        <a href="{{ route('umkm.show', $product->umkm->slug) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-neutral-500 hover:text-neutral-700">
            @svg('heroicon-o-arrow-left', 'h-4 w-4')
            Kembali ke {{ $product->umkm->name }}
        </a>

        <div class="grid gap-6 sm:grid-cols-2">
            <div class="relative aspect-square overflow-hidden rounded-3xl bg-brand-50 shadow-soft">
                @if ($product->photo_path)
                    <img src="{{ Storage::url($product->photo_path) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                @else
                    <div class="grid h-full w-full place-items-center text-brand-300">
                        @svg('heroicon-o-photo', 'h-16 w-16')
                    </div>
                @endif

                <button
                    type="button"
                    x-data
                    @click="$store.favorites.toggle({{ $product->id }})"
                    class="absolute right-3 top-3 grid h-11 w-11 place-items-center rounded-full bg-white/90 text-neutral-500 shadow-sm hover:text-rose-500"
                    :class="$store.favorites.has({{ $product->id }}) && 'text-rose-500'"
                    aria-label="Simpan ke favorit"
                >
                    <span x-show="!$store.favorites.has({{ $product->id }})">@svg('heroicon-o-heart', 'h-6 w-6')</span>
                    <span x-show="$store.favorites.has({{ $product->id }})" x-cloak>@svg('heroicon-s-heart', 'h-6 w-6')</span>
                </button>

                @if ($product->stock_status === 'habis')
                    <div class="absolute inset-0 grid place-items-center bg-neutral-900/50">
                        <span class="rounded-full bg-white px-4 py-1.5 text-sm font-bold text-neutral-800">Stok Habis</span>
                    </div>
                @endif
            </div>

            <div class="space-y-4">
                <div>
                    <span class="rounded-full bg-brand-50 px-2 py-1 text-xs font-semibold text-brand-700">
                        {{ $product->category->name }}
                    </span>
                    <h1 class="mt-2 text-xl font-extrabold tracking-tight text-neutral-900">{{ $product->name }}</h1>
                    <p class="text-sm text-neutral-500">
                        dari
                        <a href="{{ route('umkm.show', $product->umkm->slug) }}" class="font-semibold text-brand-700 hover:underline">
                            {{ $product->umkm->name }}
                        </a>
                    </p>
                </div>

                <p class="text-2xl font-extrabold tracking-tight text-brand-700">Rp{{ number_format($product->price, 0, ',', '.') }}</p>

                @if ($product->description)
                    <p class="text-sm leading-relaxed text-neutral-600">{{ $product->description }}</p>
                @endif

                <x-wa-button :href="$product->waLink()" class="w-full" />

                @if ($product->umkm->qris_image_path)
                    <details class="rounded-2xl bg-white p-3 shadow-soft">
                        <summary class="flex cursor-pointer items-center gap-2 text-sm font-semibold text-neutral-700">
                            @svg('heroicon-o-qr-code', 'h-5 w-5 text-brand-600')
                            Bayar via QRIS
                        </summary>
                        <div class="mt-3 flex justify-center">
                            <img src="{{ Storage::url($product->umkm->qris_image_path) }}" alt="QRIS {{ $product->umkm->name }}" class="max-w-[220px] rounded-xl">
                        </div>
                    </details>
                @endif
            </div>
        </div>

        @if ($otherProducts->isNotEmpty())
            <div class="space-y-3 pt-4">
                <h2 class="text-lg font-bold text-neutral-900">Produk Lain dari {{ $product->umkm->name }}</h2>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                    @foreach ($otherProducts as $other)
                        <x-product-card :product="$other->setRelation('umkm', $product->umkm)" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
