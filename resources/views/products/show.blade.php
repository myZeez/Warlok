<x-layouts.app :title="$product->name">
    <div class="mx-auto max-w-4xl space-y-5 px-4 py-6">
        <a href="{{ route('umkm.show', $product->umkm->slug) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-neutral-500 hover:text-neutral-700">
            @svg('heroicon-o-arrow-left', 'h-4 w-4')
            Kembali ke {{ $product->umkm->name }}
        </a>

        @php
            $galleryImages = collect([$product->photo_path, ...$product->images->pluck('image_path')])
                ->filter()
                ->map(fn ($path) => Storage::url($path))
                ->values();
        @endphp

        <div class="grid gap-6 sm:grid-cols-2">
            <div
                class="relative aspect-square overflow-hidden rounded-3xl bg-brand-50 shadow-soft"
                @if ($galleryImages->count() > 1) x-data="productGallery(@js($galleryImages))" @endif
            >
                @if ($galleryImages->isNotEmpty())
                    <div
                        @if ($galleryImages->count() > 1)
                            class="flex h-full touch-pan-y transition-transform duration-300 ease-out"
                            :style="`transform: translateX(-${activeIndex * 100}%)`"
                            @pointerdown="onPointerDown($event)"
                            @pointermove="onPointerMove($event)"
                            @pointerup="onPointerUp()"
                        @else
                            class="flex h-full"
                        @endif
                    >
                        @foreach ($galleryImages as $image)
                            <img src="{{ $image }}" alt="{{ $product->name }}" class="h-full w-full shrink-0 object-cover" draggable="false">
                        @endforeach
                    </div>

                    @if ($galleryImages->count() > 1)
                        <div class="pointer-events-none absolute inset-x-0 bottom-3 flex justify-center gap-1.5">
                            <template x-for="(image, index) in images" :key="index">
                                <button
                                    type="button"
                                    @click="goTo(index)"
                                    class="pointer-events-auto h-1.5 rounded-full bg-white/70 transition-all"
                                    :class="activeIndex === index ? 'w-4 bg-white' : 'w-1.5'"
                                    :aria-label="`Foto ke-${index + 1}`"
                                ></button>
                            </template>
                        </div>
                    @endif
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

                @if ($product->stock_status !== 'habis')
                    <div class="flex gap-2">
                        <a
                            href="{{ route('products.buyNow', [$product->umkm, $product]) }}"
                            class="flex min-h-[44px] flex-1 items-center justify-center gap-2 rounded-full bg-brand-600 font-bold text-white transition hover:bg-brand-700 active:scale-[0.98]"
                        >
                            @svg('heroicon-o-bolt', 'h-5 w-5')
                            Beli Langsung
                        </a>
                        <button
                            type="button"
                            x-data
                            @click="$store.cart.add({{ $product->id }})"
                            class="flex min-h-[44px] shrink-0 items-center justify-center gap-2 rounded-full border-2 border-brand-600 px-4 font-bold text-brand-600 transition hover:bg-brand-50 active:scale-[0.98]"
                        >
                            @svg('heroicon-o-shopping-cart', 'h-5 w-5')
                            <span x-text="$store.cart.qtyFor({{ $product->id }}) > 0 ? `Di keranjang (${$store.cart.qtyFor({{ $product->id }})})` : 'Tambah'"></span>
                        </button>
                    </div>
                @endif

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

        <x-review-section
            :reviews="$product->reviews"
            :average="$product->averageRating()"
            :count="$product->reviewsCount()"
            :action="route('products.reviews.store', [$product->umkm, $product])"
        />

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
