<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Region;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] #[Title('Katalog')] class extends Component
{
    use WithPagination;

    #[Url]
    public string $q = '';

    public ?int $categoryId = null;

    public ?int $regionId = null;

    public ?float $userLat = null;

    public ?float $userLong = null;

    public string $sort = 'terbaru';

    public function mount(?Category $category = null): void
    {
        $this->categoryId = $category?->id;
        $this->regionId = session('region_id');
    }

    public function updatingQ(): void
    {
        $this->resetPage();
    }

    public function updatedCategoryId(): void
    {
        $this->resetPage();
    }

    public function updatedRegionId($value): void
    {
        $this->resetPage();
        session(['region_id' => $value ?: null]);
    }

    public function updatedSort(): void
    {
        $this->resetPage();
    }

    public function setUserLocation($lat, $long): void
    {
        $this->userLat = (float) $lat;
        $this->userLong = (float) $long;
        $this->sort = 'terdekat';
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['q', 'categoryId', 'regionId', 'sort']);
        session()->forget('region_id');
        $this->resetPage();
    }

    #[Computed]
    public function categories()
    {
        return Category::orderBy('name')->get();
    }

    #[Computed]
    public function regions()
    {
        return Region::orderBy('kelurahan')->get();
    }

    #[Computed]
    public function products(): LengthAwarePaginator
    {
        $products = Product::query()
            ->active()
            ->with(['umkm.region', 'category', 'reviews'])
            ->whereHas('umkm', fn ($query) => $query->active())
            ->when($this->q !== '', function ($query) {
                $query->where(function ($inner) {
                    $inner->where('name', 'like', "%{$this->q}%")
                        ->orWhereHas('umkm', fn ($u) => $u->where('name', 'like', "%{$this->q}%"));
                });
            })
            ->when($this->categoryId, fn ($query) => $query->where('category_id', $this->categoryId))
            ->when($this->regionId, fn ($query) => $query->whereHas('umkm', fn ($u) => $u->where('region_id', $this->regionId)))
            ->get();

        if ($this->userLat !== null && $this->userLong !== null) {
            $products = $products->each(
                fn (Product $product) => $product->distanceKm = $product->umkm->distanceInKmFrom($this->userLat, $this->userLong)
            );
        }

        $products = (match ($this->sort) {
            'terdekat' => $products->sortBy('distanceKm'),
            'termurah' => $products->sortBy('price'),
            'termahal' => $products->sortByDesc('price'),
            default => $products->sortByDesc('created_at'),
        })->values();

        $perPage = 12;
        $page = $this->getPage();

        return new LengthAwarePaginator(
            $products->forPage($page, $perPage),
            $products->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()],
        );
    }
}; ?>

<div class="mx-auto max-w-6xl space-y-5 px-4 py-6">
    <div x-data="{
        askLocation() {
            if (! navigator.geolocation) return;
            navigator.geolocation.getCurrentPosition(
                (pos) => $wire.setUserLocation(pos.coords.latitude, pos.coords.longitude),
                () => {},
            );
        },
    }">
        <x-page-logo />

        <h1 class="text-xl font-extrabold tracking-tight text-neutral-900">Katalog</h1>

        {{-- Search + filters --}}
        <div class="mt-4 space-y-3">
            <div class="relative">
                <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-neutral-400">
                    @svg('heroicon-o-magnifying-glass', 'h-5 w-5')
                </span>
                <input
                    type="text"
                    wire:model.live.debounce.400ms="q"
                    placeholder="Cari produk atau nama toko..."
                    class="w-full rounded-full bg-white py-3 pl-12 pr-4 text-sm shadow-soft focus:outline-none focus:ring-2 focus:ring-brand-500/30"
                >
            </div>

            <div class="-mx-4 flex gap-2 overflow-x-auto px-4 pb-1">
                <select wire:model.live="categoryId" class="min-w-[140px] rounded-full bg-white px-3 py-2 text-sm shadow-soft">
                    <option value="">Semua Kategori</option>
                    @foreach ($this->categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="regionId" class="min-w-[140px] rounded-full bg-white px-3 py-2 text-sm shadow-soft">
                    <option value="">Semua Wilayah</option>
                    @foreach ($this->regions as $region)
                        <option value="{{ $region->id }}">{{ $region->kelurahan }}</option>
                    @endforeach
                </select>

                <select wire:model.live="sort" class="min-w-[130px] rounded-full bg-white px-3 py-2 text-sm shadow-soft">
                    <option value="terbaru">Terbaru</option>
                    <option value="termurah">Termurah</option>
                    <option value="termahal">Termahal</option>
                    <option value="terdekat">Terdekat</option>
                </select>

                <button
                    type="button"
                    @click="askLocation()"
                    class="flex shrink-0 items-center gap-1 rounded-full bg-brand-50 px-3 py-2 text-sm font-semibold text-brand-700 shadow-soft"
                >
                    @svg('heroicon-o-map-pin', 'h-4 w-4')
                    Terdekat
                </button>

                @if ($q !== '' || $categoryId || $regionId || $sort !== 'terbaru')
                    <button
                        type="button"
                        wire:click="resetFilters"
                        class="shrink-0 rounded-full px-3 py-2 text-sm font-semibold text-neutral-500 hover:text-neutral-700"
                    >
                        Reset
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Results --}}
    <div wire:loading.class="opacity-50" class="transition-opacity">
        @if ($this->products->isEmpty())
            <x-empty-state
                icon="heroicon-o-magnifying-glass"
                title="Produk tidak ditemukan"
                description="Coba kata kunci lain atau ubah filter kategori/wilayah."
            />
        @else
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($this->products as $product)
                    <x-product-card :product="$product" :distanceKm="$product->distanceKm ?? null" />
                @endforeach
            </div>

            <div class="mt-6">
                {{ $this->products->links() }}
            </div>
        @endif
    </div>
</div>
