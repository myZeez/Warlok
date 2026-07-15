<?php

use App\Models\Product;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts.app')] #[Title('Keranjang')] class extends Component
{
    /** @var array<int, array{productId: int, qty: int}> */
    public array $cartItems = [];

    public bool $loaded = false;

    public function loadCart(array $items): void
    {
        $this->cartItems = collect($items)
            ->filter(fn ($item) => isset($item['productId'], $item['qty']))
            ->map(fn ($item) => ['productId' => (int) $item['productId'], 'qty' => max(1, (int) $item['qty'])])
            ->values()
            ->all();

        $this->loaded = true;
    }

    public function updateQty(int $productId, int $qty): void
    {
        if ($qty <= 0) {
            $this->removeItem($productId);

            return;
        }

        $this->cartItems = collect($this->cartItems)
            ->map(fn ($item) => $item['productId'] === $productId ? [...$item, 'qty' => $qty] : $item)
            ->all();

        $this->syncLocalStorage();
    }

    public function removeItem(int $productId): void
    {
        $this->cartItems = collect($this->cartItems)
            ->reject(fn ($item) => $item['productId'] === $productId)
            ->values()
            ->all();

        $this->syncLocalStorage();
    }

    protected function syncLocalStorage(): void
    {
        $this->dispatch('cart-updated', items: $this->cartItems);
    }

    #[Computed]
    public function groups(): Collection
    {
        if (empty($this->cartItems)) {
            return collect();
        }

        $productIds = collect($this->cartItems)->pluck('productId');

        $products = Product::query()
            ->active()
            ->whereIn('id', $productIds)
            ->with('umkm')
            ->get()
            ->keyBy('id');

        return collect($this->cartItems)
            ->map(function ($item) use ($products) {
                $product = $products->get($item['productId']);

                if (! $product) {
                    return null;
                }

                return [
                    'product' => $product,
                    'qty' => $item['qty'],
                    'subtotal' => $product->price * $item['qty'],
                ];
            })
            ->filter()
            ->values()
            ->groupBy(fn ($row) => $row['product']->umkm_id);
    }
}; ?>

<div
    x-data
    x-init="if (! $wire.loaded) $wire.loadCart(JSON.parse(localStorage.getItem('warlok_cart') || '[]'))"
    x-on:cart-updated.window="
        localStorage.setItem('warlok_cart', JSON.stringify($event.detail.items));
        $store.cart.items = $event.detail.items;
    "
    class="mx-auto max-w-4xl space-y-5 px-4 py-6"
>
    <x-page-logo />

    <h1 class="text-xl font-extrabold text-neutral-900">Keranjang</h1>

    @if (! $loaded)
        <p class="mt-6 text-sm text-neutral-400">Memuat keranjang...</p>
    @elseif ($this->groups->isEmpty())
        <x-empty-state
            icon="heroicon-o-shopping-cart"
            title="Keranjang kosong"
            description="Ketuk tombol tambah di halaman produk untuk memasukkannya ke sini."
        />
    @else
        @foreach ($this->groups as $umkmId => $rows)
            @php
                $umkm = $rows->first()['product']->umkm;
                $subtotal = $rows->sum('subtotal');
                $methods = $umkm->enabledDeliveryMethods();
                $methodLabels = ['pickup' => 'Ambil di Toko', 'self_delivery' => 'Antar Sendiri', 'gojek' => 'Gojek', 'grab' => 'Grab'];
            @endphp

            <div class="space-y-4 rounded-3xl bg-white p-4 shadow-soft">
                <a href="{{ route('umkm.show', $umkm->slug) }}" class="text-sm font-bold text-neutral-900 hover:underline">
                    {{ $umkm->name }}
                </a>

                <div class="space-y-3">
                    @foreach ($rows as $row)
                        <div class="flex items-center gap-3">
                            <div class="h-14 w-14 shrink-0 overflow-hidden rounded-xl bg-brand-50">
                                @if ($row['product']->photo_path)
                                    <img src="{{ Storage::url($row['product']->photo_path) }}" alt="{{ $row['product']->name }}" class="h-full w-full object-cover">
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-neutral-800">{{ $row['product']->name }}</p>
                                <p class="text-xs text-neutral-500">Rp{{ number_format($row['product']->price, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="updateQty({{ $row['product']->id }}, {{ $row['qty'] - 1 }})" class="grid h-8 w-8 place-items-center rounded-full bg-neutral-100 text-neutral-600 hover:bg-neutral-200">
                                    @svg('heroicon-o-minus', 'h-3.5 w-3.5')
                                </button>
                                <span class="w-5 text-center text-sm font-semibold">{{ $row['qty'] }}</span>
                                <button type="button" wire:click="updateQty({{ $row['product']->id }}, {{ $row['qty'] + 1 }})" class="grid h-8 w-8 place-items-center rounded-full bg-neutral-100 text-neutral-600 hover:bg-neutral-200">
                                    @svg('heroicon-o-plus', 'h-3.5 w-3.5')
                                </button>
                            </div>
                            <button type="button" wire:click="removeItem({{ $row['product']->id }})" class="text-neutral-400 hover:text-rose-500" aria-label="Hapus">
                                @svg('heroicon-o-trash', 'h-4 w-4')
                            </button>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between border-t border-neutral-100 pt-3 text-sm font-bold text-neutral-900">
                    <span>Subtotal</span>
                    <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>

                @if (empty($methods))
                    <p class="rounded-xl bg-amber-50 p-3 text-xs text-amber-800">Toko ini belum mengaktifkan opsi pengiriman apa pun, jadi belum bisa checkout untuk saat ini.</p>
                @else
                    <form
                        action="{{ route('orders.store', $umkm) }}"
                        method="POST"
                        class="space-y-3 border-t border-neutral-100 pt-3"
                    >
                        @csrf

                        {{-- Hidden qty inputs stay outside wire:ignore so they always reflect the
                             latest quantities -- only the typed buyer fields below need to survive
                             Livewire's re-renders untouched. --}}
                        @foreach ($rows as $row)
                            <input type="hidden" name="items[{{ $loop->index }}][product_id]" value="{{ $row['product']->id }}">
                            <input type="hidden" name="items[{{ $loop->index }}][qty]" value="{{ $row['qty'] }}">
                        @endforeach

                        <div wire:ignore x-data="{ method: '{{ array_key_first($methods) }}' }" class="space-y-3">
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-neutral-700">Metode Pengiriman</label>
                                <select name="delivery_method" x-model="method" class="w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25">
                                    @foreach ($methods as $key => $enabled)
                                        <option value="{{ $key }}">{{ $methodLabels[$key] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="mb-1 block text-xs font-semibold text-neutral-700">Nama</label>
                                <input type="text" name="buyer_name" required class="w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25">
                            </div>

                            <div>
                                <label class="mb-1 block text-xs font-semibold text-neutral-700">Nomor WhatsApp</label>
                                <input type="text" name="buyer_phone" required placeholder="081234567890" class="w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25">
                            </div>

                            <div x-show="method === 'self_delivery' || method === 'gojek' || method === 'grab'">
                                <label class="mb-1 block text-xs font-semibold text-neutral-700">Alamat Pengiriman</label>
                                <textarea name="buyer_address" rows="2" class="w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25"></textarea>
                            </div>

                            <div>
                                <label class="mb-1 block text-xs font-semibold text-neutral-700">Catatan untuk Toko (opsional)</label>
                                <textarea name="notes" rows="2" placeholder="Contoh: jangan pedas, tolong dibungkus rapi" class="w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25"></textarea>
                            </div>
                        </div>

                        <button type="submit" class="flex min-h-[44px] w-full items-center justify-center rounded-full bg-brand-600 font-bold text-white hover:bg-brand-700">
                            Checkout Toko Ini
                        </button>
                    </form>
                @endif
            </div>
        @endforeach
    @endif
</div>
