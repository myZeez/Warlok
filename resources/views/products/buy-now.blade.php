<x-layouts.app :title="'Beli '.$product->name">
    <div class="mx-auto max-w-xl space-y-5 px-4 py-6">
        <a href="{{ route('products.show', [$umkm, $product]) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-neutral-500 hover:text-neutral-700">
            @svg('heroicon-o-arrow-left', 'h-4 w-4')
            Kembali ke produk
        </a>

        <div>
            <x-page-logo />
            <h1 class="text-xl font-extrabold tracking-tight text-neutral-900">Beli Langsung</h1>
        </div>

        <div class="flex items-center gap-3 rounded-2xl bg-white p-4 shadow-soft">
            <div class="h-16 w-16 shrink-0 overflow-hidden rounded-xl bg-brand-50">
                @if ($product->photo_path)
                    <img src="{{ Storage::url($product->photo_path) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                @endif
            </div>
            <div class="min-w-0 flex-1">
                <p class="truncate font-semibold text-neutral-800">{{ $product->name }}</p>
                <p class="text-sm text-neutral-500">{{ $umkm->name }}</p>
                <p class="font-bold text-brand-700">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
            </div>
        </div>

        @if (empty($methods))
            <x-empty-state
                icon="heroicon-o-truck"
                title="Belum bisa checkout"
                description="Toko ini belum mengaktifkan opsi pengiriman apa pun. Coba pesan langsung lewat WhatsApp."
            />
            <x-wa-button :href="$product->waLink()" class="w-full" />
        @else
            <form
                action="{{ route('orders.store', $umkm) }}"
                method="POST"
                class="space-y-4 rounded-3xl bg-white p-5 shadow-soft"
                x-data="{ method: '{{ old('delivery_method', array_key_first($methods)) }}', qty: {{ (int) old('items.0.qty', 1) }} }"
            >
                @csrf

                <input type="hidden" name="items[0][product_id]" value="{{ $product->id }}">
                <input type="hidden" name="items[0][qty]" :value="qty">

                <div>
                    <label class="mb-1 block text-sm font-semibold text-neutral-700">Jumlah</label>
                    <div class="flex items-center gap-3">
                        <button type="button" @click="qty = Math.max(1, qty - 1)" class="grid h-10 w-10 place-items-center rounded-full bg-neutral-100 text-neutral-600 hover:bg-neutral-200">
                            @svg('heroicon-o-minus', 'h-4 w-4')
                        </button>
                        <span class="w-8 text-center text-sm font-semibold" x-text="qty"></span>
                        <button type="button" @click="qty = Math.min(99, qty + 1)" class="grid h-10 w-10 place-items-center rounded-full bg-neutral-100 text-neutral-600 hover:bg-neutral-200">
                            @svg('heroicon-o-plus', 'h-4 w-4')
                        </button>
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-neutral-700">Metode Pengiriman</label>
                    <select name="delivery_method" x-model="method" class="w-full rounded-xl border border-neutral-200 px-4 py-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25">
                        @foreach ($methods as $key => $enabled)
                            <option value="{{ $key }}">
                                {{ match ($key) {
                                    'pickup' => 'Ambil di Toko',
                                    'self_delivery' => 'Antar Sendiri',
                                    'gojek' => 'Gojek',
                                    'grab' => 'Grab',
                                } }}
                            </option>
                        @endforeach
                    </select>
                    @error('delivery_method') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-neutral-700">Nama</label>
                    <input type="text" name="buyer_name" value="{{ old('buyer_name') }}" required class="w-full rounded-xl border border-neutral-200 px-4 py-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25">
                    @error('buyer_name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-neutral-700">Nomor WhatsApp</label>
                    <input type="text" name="buyer_phone" value="{{ old('buyer_phone') }}" required placeholder="081234567890" class="w-full rounded-xl border border-neutral-200 px-4 py-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25">
                    @error('buyer_phone') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div x-show="method === 'self_delivery' || method === 'gojek' || method === 'grab'">
                    <label class="mb-1 block text-sm font-semibold text-neutral-700">Alamat Pengiriman</label>
                    <textarea name="buyer_address" rows="2" class="w-full rounded-xl border border-neutral-200 px-4 py-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25">{{ old('buyer_address') }}</textarea>
                    @error('buyer_address') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-neutral-700">Catatan untuk Toko (opsional)</label>
                    <textarea name="notes" rows="2" class="w-full rounded-xl border border-neutral-200 px-4 py-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25">{{ old('notes') }}</textarea>
                </div>

                @error('items') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror

                <button type="submit" class="flex min-h-[44px] w-full items-center justify-center rounded-full bg-brand-600 font-bold text-white hover:bg-brand-700">
                    Buat Pesanan
                </button>
            </form>
        @endif
    </div>
</x-layouts.app>
