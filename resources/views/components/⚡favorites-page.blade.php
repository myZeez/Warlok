<?php

use App\Models\Product;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts.app')] #[Title('Favorit')] class extends Component
{
    public array $productIds = [];

    public bool $loaded = false;

    public function loadFavorites(array $ids): void
    {
        $this->productIds = array_values(array_filter(array_map('intval', $ids)));
        $this->loaded = true;
    }

    #[Computed]
    public function products(): Collection
    {
        if (empty($this->productIds)) {
            return collect();
        }

        return Product::query()
            ->active()
            ->whereIn('id', $this->productIds)
            ->with(['umkm', 'category'])
            ->get();
    }
}; ?>

<div
    x-data
    x-init="$wire.loadFavorites(JSON.parse(localStorage.getItem('warlok_favorites') || '[]'))"
    class="mx-auto max-w-6xl px-4 py-6"
>
    <h1 class="text-xl font-extrabold text-neutral-900">Favorit Saya</h1>

    @if (! $loaded)
        <p class="mt-6 text-sm text-neutral-400">Memuat favorit...</p>
    @elseif ($this->products->isEmpty())
        <div class="mt-6">
            <x-empty-state
                icon="heroicon-o-heart"
                title="Belum ada favorit"
                description="Ketuk ikon hati di halaman produk untuk menyimpannya di sini."
            />
        </div>
    @else
        <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($this->products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    @endif
</div>
