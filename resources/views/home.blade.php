<x-layouts.app>
    <x-hero-banner>
        <form action="{{ route('catalog.index') }}" method="GET">
            <div class="relative">
                <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-neutral-400">
                    @svg('heroicon-o-magnifying-glass', 'h-5 w-5')
                </span>
                <input
                    type="text"
                    name="q"
                    placeholder="Cari nama produk, jasa, atau toko..."
                    class="w-full rounded-full bg-white py-4 pl-12 pr-4 text-sm shadow-soft-lg focus:outline-none focus:ring-2 focus:ring-brand-500/30"
                >
            </div>
        </form>
    </x-hero-banner>

    <div class="mx-auto max-w-7xl space-y-16 px-4 pb-16 pt-4 sm:px-6 lg:px-8">
        {{-- Category chips --}}
        <section class="space-y-5 text-center">
            <h2 class="text-lg font-bold text-neutral-900">Kategori</h2>
            <div
                x-data="{ revealed: false }"
                x-intersect.once="revealed = true"
                class="-mx-4 flex flex-nowrap gap-3 overflow-x-auto px-4 pb-2 sm:mx-0 sm:flex-wrap sm:justify-center sm:gap-4 sm:overflow-visible sm:px-0 sm:pb-0"
            >
                @foreach ($categories as $index => $category)
                    <div
                        :class="revealed ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                        class="transition-all duration-500 ease-[cubic-bezier(0.25,1,0.5,1)] motion-reduce:transition-none"
                        style="transition-delay: {{ $index * 60 }}ms"
                    >
                        <x-category-chip :category="$category" />
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Real stats: not a fabricated social-proof band, just the actual catalog size --}}
        <section
            x-data="{ revealed: false }"
            x-intersect.once="revealed = true"
            class="grid grid-cols-3 gap-3 sm:gap-4"
        >
            <div
                :class="revealed ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                class="rounded-2xl bg-white p-4 text-center shadow-soft transition-all duration-500 ease-[cubic-bezier(0.25,1,0.5,1)] motion-reduce:transition-none sm:p-5"
            >
                <p class="text-2xl font-extrabold tracking-tight text-brand-700 sm:text-3xl">{{ $stats['umkmCount'] }}</p>
                <p class="text-xs text-neutral-500 sm:text-sm">UMKM Aktif</p>
            </div>
            <div
                :class="revealed ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                class="rounded-2xl bg-white p-4 text-center shadow-soft transition-all duration-500 ease-[cubic-bezier(0.25,1,0.5,1)] motion-reduce:transition-none sm:p-5"
                style="transition-delay: 80ms"
            >
                <p class="text-2xl font-extrabold tracking-tight text-brand-700 sm:text-3xl">{{ $stats['productCount'] }}</p>
                <p class="text-xs text-neutral-500 sm:text-sm">Produk Tersedia</p>
            </div>
            <div
                :class="revealed ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                class="rounded-2xl bg-white p-4 text-center shadow-soft transition-all duration-500 ease-[cubic-bezier(0.25,1,0.5,1)] motion-reduce:transition-none sm:p-5"
                style="transition-delay: 160ms"
            >
                <p class="text-2xl font-extrabold tracking-tight text-brand-700 sm:text-3xl">{{ $stats['regionCount'] }}</p>
                <p class="text-xs text-neutral-500 sm:text-sm">Wilayah</p>
            </div>
        </section>

        {{-- Featured UMKM: stacked swipe carousel --}}
        <section
            x-data="{ revealed: false }"
            x-intersect.once="revealed = true"
            :class="revealed ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
            class="space-y-6 transition-all duration-700 ease-[cubic-bezier(0.25,1,0.5,1)] motion-reduce:transition-none"
        >
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-neutral-900">UMKM Pilihan</h2>
                <a href="{{ route('catalog.index') }}" class="text-sm font-semibold text-brand-700">Lihat semua</a>
            </div>

            @if ($featuredUmkms->isEmpty())
                <x-empty-state
                    icon="heroicon-o-building-storefront"
                    title="Belum ada UMKM aktif"
                    description="UMKM di wilayah ini akan muncul di sini setelah disetujui admin."
                />
            @else
                <div
                    x-data="stackedCarousel(@js($featuredUmkms))"
                    class="relative mx-auto h-80 max-w-sm touch-pan-y select-none sm:h-96 sm:max-w-md lg:h-[27rem] lg:max-w-xl"
                >
                    <template x-for="(item, index) in items.slice(0, 4)" :key="item.id">
                        <div
                            class="absolute inset-x-6 top-0 cursor-grab touch-none rounded-3xl bg-white p-5 shadow-soft-lg active:cursor-grabbing sm:p-6 lg:inset-x-10 lg:p-8"
                            :style="`transform: ${cardTransform(index)}; z-index: ${cardZIndex(index)}; transition: ${cardTransition(index)};`"
                            @pointerdown="index === 0 && onPointerDown($event)"
                            @pointermove.window="index === 0 && onPointerMove($event)"
                            @pointerup.window="index === 0 && onPointerUp()"
                        >
                            <div class="flex items-center gap-3 lg:gap-4">
                                <template x-if="item.logoUrl">
                                    <img :src="item.logoUrl" class="h-14 w-14 rounded-2xl object-cover sm:h-16 sm:w-16 lg:h-20 lg:w-20" alt="">
                                </template>
                                <template x-if="!item.logoUrl">
                                    <div class="grid h-14 w-14 place-items-center rounded-2xl bg-brand-50 text-xl font-extrabold text-brand-600 sm:h-16 sm:w-16 sm:text-2xl lg:h-20 lg:w-20 lg:text-3xl" x-text="item.initial"></div>
                                </template>
                                <div class="min-w-0">
                                    <p class="truncate font-bold text-neutral-900 sm:text-lg lg:text-xl" x-text="item.name"></p>
                                    <p class="truncate text-sm text-neutral-500 lg:text-base" x-text="item.region"></p>
                                    <span
                                        class="mt-1 inline-block rounded-full px-2 py-0.5 text-[11px] font-semibold sm:text-xs"
                                        :class="item.isOpen ? 'bg-brand-100 text-brand-700' : 'bg-neutral-100 text-neutral-500'"
                                        x-text="item.isOpen ? 'Buka' : 'Tutup'"
                                    ></span>
                                </div>
                            </div>
                            <a
                                :href="item.url"
                                @pointerdown.stop
                                class="mt-5 flex min-h-[44px] items-center justify-center rounded-full bg-brand-600 font-semibold text-white hover:bg-brand-700 sm:mt-8 lg:text-lg"
                            >
                                Lihat Toko
                            </a>
                        </div>
                    </template>
                </div>
                <p class="text-center text-xs text-neutral-400">Geser kartu untuk lihat UMKM lainnya</p>
            @endif
        </section>

        {{-- Featured products: tilted card mosaic --}}
        <section
            x-data="{ revealed: false }"
            x-intersect.once="revealed = true"
            :class="revealed ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
            class="space-y-5 transition-all duration-700 ease-[cubic-bezier(0.25,1,0.5,1)] motion-reduce:transition-none"
        >
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-neutral-900">Produk Pilihan</h2>
                <a href="{{ route('catalog.index') }}" class="text-sm font-semibold text-brand-700">Lihat semua</a>
            </div>

            @if ($featuredProducts->isEmpty())
                <x-empty-state
                    icon="heroicon-o-shopping-bag"
                    title="Belum ada produk"
                    description="Produk dari UMKM aktif akan tampil di sini."
                />
            @else
                @php $featuredCount = $featuredProducts->count(); @endphp
                <div x-data="featuredCarousel({{ $featuredCount }})" x-init="init()" class="relative -mx-4 sm:mx-0">
                    <div
                        x-ref="track"
                        @mouseenter="stop()"
                        @mouseleave="play()"
                        @touchstart="stop()"
                        class="flex snap-x snap-mandatory gap-5 overflow-x-auto scroll-smooth px-[calc(50%-6rem)] py-8 [scrollbar-width:none] sm:px-[calc(50%-7rem)] lg:px-[calc(50%-8rem)] [&::-webkit-scrollbar]:hidden"
                    >
                        @for ($pass = 0; $pass < 2; $pass++)
                            @foreach ($featuredProducts as $i => $product)
                                <div
                                    class="w-48 shrink-0 snap-center transition-all duration-500 ease-out sm:w-56 lg:w-64"
                                    :class="isActive({{ $pass * $featuredCount + $i }}) ? 'scale-110 z-10' : 'scale-90 opacity-50'"
                                >
                                    <x-product-card :product="$product" />
                                </div>
                            @endforeach
                        @endfor
                    </div>
                </div>
            @endif
        </section>

        <x-home-testimonials />

        <x-why-warlok />

        {{-- CTA: register UMKM --}}
        <section
            x-data="{ revealed: false }"
            x-intersect.once="revealed = true"
            :class="revealed ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
            class="transition-all duration-700 ease-[cubic-bezier(0.25,1,0.5,1)] motion-reduce:transition-none"
        >
            <div class="flex flex-col items-center gap-3 rounded-3xl bg-neutral-900 px-6 py-10 text-center sm:py-12">
                @svg('heroicon-o-megaphone', 'h-8 w-8 text-brand-400')
                <p class="text-lg font-bold text-white sm:text-xl">Punya usaha di sekitar sini?</p>
                <p class="max-w-sm text-sm text-neutral-400">
                    Daftarkan UMKM kamu di Warlok, gratis, dan mulai dilihat tetangga sekitar.
                </p>
                <a
                    href="{{ route('umkm.register') }}"
                    class="mt-2 flex min-h-[44px] items-center justify-center rounded-full bg-brand-600 px-6 font-bold text-white hover:bg-brand-700"
                >
                    Daftarkan UMKM
                </a>
            </div>
        </section>
    </div>
</x-layouts.app>
