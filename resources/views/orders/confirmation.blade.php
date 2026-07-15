<x-layouts.app title="Pesanan Berhasil">
    <div
        x-data
        x-init="
            const orderedIds = @js($order->items->pluck('product_id'));
            const remaining = JSON.parse(localStorage.getItem('warlok_cart') || '[]').filter((item) => !orderedIds.includes(item.productId));
            localStorage.setItem('warlok_cart', JSON.stringify(remaining));
            $store.cart.items = remaining;
        "
        class="mx-auto max-w-xl space-y-5 px-4 py-6"
    >
        <x-page-logo />

        <div class="rounded-3xl bg-white p-5 text-center shadow-soft">
            @svg('heroicon-o-check-circle', 'mx-auto h-12 w-12 text-brand-600')
            <h1 class="mt-3 text-xl font-extrabold text-neutral-900">Pesanan Berhasil Dibuat!</h1>
            <p class="mt-1 text-sm text-neutral-500">
                Konfirmasi pesananmu ke {{ $order->umkm->name }} lewat WhatsApp untuk menyelesaikan pemesanan.
            </p>
        </div>

        <div class="space-y-3 rounded-3xl bg-white p-5 shadow-soft">
            <h2 class="text-sm font-bold text-neutral-900">Ringkasan Pesanan</h2>

            @foreach ($order->items as $item)
                <div class="flex justify-between text-sm text-neutral-700">
                    <span>{{ $item->quantity }}x {{ $item->product_name }}</span>
                    <span>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach

            <div class="space-y-1 border-t border-neutral-100 pt-2">
                <div class="flex justify-between text-sm text-neutral-500">
                    <span>Subtotal</span>
                    <span>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm text-neutral-500">
                    <span>{{ $order->deliveryMethodLabel() }}</span>
                    <span>Rp{{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm font-bold text-neutral-900">
                    <span>Total</span>
                    <span>Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            @if ($order->notes)
                <div class="border-t border-neutral-100 pt-2 text-sm text-neutral-600">
                    <span class="font-semibold text-neutral-700">Catatan:</span> {{ $order->notes }}
                </div>
            @endif
        </div>

        @if ($order->umkm->qris_image_path)
            <div class="rounded-3xl bg-white p-5 text-center shadow-soft">
                <h2 class="text-sm font-bold text-neutral-900">Bayar via QRIS</h2>
                <img src="{{ Storage::url($order->umkm->qris_image_path) }}" alt="QRIS {{ $order->umkm->name }}" class="mx-auto mt-3 max-w-[220px] rounded-xl">
            </div>
        @endif

        <x-wa-button :href="$order->umkm->waLink($order->waSummaryMessage())" label="Konfirmasi via WhatsApp" class="w-full" />

        <a href="{{ route('catalog.index') }}" class="block text-center text-sm font-semibold text-neutral-500 hover:text-neutral-700">
            Lanjut belanja
        </a>
    </div>
</x-layouts.app>
