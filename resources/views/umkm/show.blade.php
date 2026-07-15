<x-layouts.app :title="$umkm->name">
    <div class="mx-auto max-w-4xl space-y-5 px-4 py-6">
        <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-neutral-500 hover:text-neutral-700">
            @svg('heroicon-o-arrow-left', 'h-4 w-4')
            Kembali ke katalog
        </a>

        <div class="rounded-3xl bg-white p-5 shadow-soft">
            <div class="flex items-start gap-4">
                <div class="h-20 w-20 shrink-0 overflow-hidden rounded-2xl bg-brand-50">
                    @if ($umkm->logo_path)
                        <img src="{{ Storage::url($umkm->logo_path) }}" alt="{{ $umkm->name }}" class="h-full w-full object-cover">
                    @else
                        <div class="grid h-full w-full place-items-center text-2xl font-extrabold text-brand-600">
                            {{ Str::of($umkm->name)->substr(0, 1)->upper() }}
                        </div>
                    @endif
                </div>

                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-xl font-extrabold tracking-tight text-neutral-900">{{ $umkm->name }}</h1>
                        <span class="rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $umkm->is_open ? 'bg-brand-100 text-brand-700' : 'bg-neutral-100 text-neutral-500' }}">
                            {{ $umkm->is_open ? 'Buka' : 'Tutup' }}
                        </span>
                    </div>
                    <p class="text-sm text-neutral-500">{{ $umkm->region->full_name }}</p>
                    @if ($umkm->description)
                        <p class="mt-2 text-sm text-neutral-600">{{ $umkm->description }}</p>
                    @endif
                </div>
            </div>

            <div class="mt-5 flex flex-col gap-2 sm:flex-row">
                <x-wa-button :href="$umkm->waLink()" class="flex-1" />
            </div>

            <div class="mt-3 flex flex-wrap gap-2">
                @if ($umkm->pickup_enabled)
                    <span class="inline-flex items-center gap-1 rounded-full bg-neutral-100 px-2.5 py-1 text-xs font-semibold text-neutral-600">
                        @svg('heroicon-o-building-storefront', 'h-3.5 w-3.5')
                        Ambil di Toko
                    </span>
                @endif
                @if ($umkm->delivery_self_enabled)
                    <span class="inline-flex items-center gap-1 rounded-full bg-neutral-100 px-2.5 py-1 text-xs font-semibold text-neutral-600">
                        @svg('heroicon-o-truck', 'h-3.5 w-3.5')
                        Antar Sendiri
                        @if ($umkm->delivery_self_fee > 0)
                            (Rp{{ number_format($umkm->delivery_self_fee, 0, ',', '.') }})
                        @else
                            (Gratis)
                        @endif
                    </span>
                @endif
                @if ($umkm->delivery_gojek_enabled || $umkm->delivery_grab_enabled)
                    <span class="inline-flex items-center gap-1 rounded-full bg-neutral-100 px-2.5 py-1 text-xs font-semibold text-neutral-600">
                        @svg('heroicon-o-truck', 'h-3.5 w-3.5')
                        {{ collect([$umkm->delivery_gojek_enabled ? 'Gojek' : null, $umkm->delivery_grab_enabled ? 'Grab' : null])->filter()->implode('/') }} tersedia
                    </span>
                @endif
            </div>

            <div class="mt-4 flex items-start gap-2 text-sm text-neutral-500">
                @svg('heroicon-o-map-pin', 'mt-0.5 h-4 w-4 shrink-0')
                <span>{{ $umkm->address }}</span>
            </div>

            @if ($umkm->lat && $umkm->long)
                <div class="mt-3 space-y-2">
                    <div x-data="umkmMap({{ $umkm->lat }}, {{ $umkm->long }}, @js($umkm->name))" x-init="init()">
                        <div x-ref="map" class="h-48 w-full overflow-hidden rounded-2xl border border-neutral-200"></div>
                    </div>
                    <a
                        href="https://www.google.com/maps?q={{ $umkm->lat }},{{ $umkm->long }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-1 text-sm font-semibold text-brand-700 hover:underline"
                    >
                        @svg('heroicon-o-arrow-top-right-on-square', 'h-4 w-4')
                        Buka di Google Maps
                    </a>
                </div>
            @endif

            @if ($umkm->qris_image_path)
                <details class="mt-4 rounded-2xl bg-neutral-50 p-3">
                    <summary class="flex cursor-pointer items-center gap-2 text-sm font-semibold text-neutral-700">
                        @svg('heroicon-o-qr-code', 'h-5 w-5 text-brand-600')
                        Bayar via QRIS
                    </summary>
                    <div class="mt-3 flex justify-center">
                        <img src="{{ Storage::url($umkm->qris_image_path) }}" alt="QRIS {{ $umkm->name }}" class="max-w-[220px] rounded-xl">
                    </div>
                </details>
            @endif
        </div>

        <div class="space-y-3">
            <h2 class="text-lg font-bold text-neutral-900">Produk ({{ $umkm->products->count() }})</h2>

            @if ($umkm->products->isEmpty())
                <x-empty-state
                    icon="heroicon-o-shopping-bag"
                    title="Belum ada produk"
                    description="UMKM ini belum menambahkan produk."
                />
            @else
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                    @foreach ($umkm->products as $product)
                        <x-product-card :product="$product->setRelation('umkm', $umkm)" />
                    @endforeach
                </div>
            @endif
        </div>

        <x-review-section
            :reviews="$umkm->reviews"
            :average="$umkm->averageRating()"
            :count="$umkm->reviewsCount()"
            :action="route('umkm.reviews.store', $umkm)"
        />
    </div>
</x-layouts.app>
