<x-layouts.app title="Pesanan Saya">
    <div class="mx-auto max-w-2xl space-y-5 px-4 py-6">
        <x-page-logo />

        <h1 class="text-xl font-extrabold tracking-tight text-neutral-900">Pesanan Saya</h1>

        @if ($orders->isEmpty())
            <x-empty-state
                icon="heroicon-o-clipboard-document-list"
                title="Belum ada pesanan"
                description="Pesanan yang kamu buat akan muncul di sini."
            />
        @else
            <div class="space-y-3">
                @foreach ($orders as $order)
                    <div class="rounded-2xl bg-white p-4 shadow-soft">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <a href="{{ route('umkm.show', $order->umkm->slug) }}" class="font-bold text-neutral-900 hover:underline">
                                    {{ $order->umkm->name }}
                                </a>
                                <p class="text-xs text-neutral-400">{{ $order->created_at->diffForHumans() }}</p>
                            </div>
                            <span
                                @class([
                                    'shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold',
                                    'bg-amber-100 text-amber-700' => $order->status === 'pending',
                                    'bg-blue-100 text-blue-700' => $order->status === 'confirmed',
                                    'bg-brand-100 text-brand-700' => $order->status === 'completed',
                                    'bg-neutral-100 text-neutral-500' => $order->status === 'cancelled',
                                ])
                            >
                                {{ $order->statusLabel() }}
                            </span>
                        </div>

                        <div class="mt-3 space-y-1">
                            @foreach ($order->items as $item)
                                <p class="text-sm text-neutral-600">{{ $item->quantity }}x {{ $item->product_name }}</p>
                            @endforeach
                        </div>

                        <div class="mt-2 flex justify-between border-t border-neutral-100 pt-2 text-sm font-bold text-neutral-900">
                            <span>Total</span>
                            <span>Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>

                        @if ($order->seller_note)
                            <div class="mt-3 rounded-xl bg-brand-50 p-3 text-sm text-brand-800">
                                <span class="font-semibold">Catatan dari toko:</span> {{ $order->seller_note }}
                            </div>
                        @endif

                        <a href="{{ route('orders.confirmation', $order) }}" class="mt-3 inline-block text-sm font-semibold text-brand-700 hover:underline">
                            Lihat detail &amp; konfirmasi WhatsApp
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>
