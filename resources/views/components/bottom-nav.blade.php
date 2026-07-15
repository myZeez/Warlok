@php
    $navItems = [
        ['label' => 'Home', 'route' => 'home', 'icon' => 'home'],
        ['label' => 'Kategori', 'route' => 'catalog.index', 'icon' => 'squares-2x2'],
        ['label' => 'Keranjang', 'route' => 'cart.index', 'icon' => 'shopping-cart'],
        ['label' => 'Favorit', 'route' => 'favorites.index', 'icon' => 'heart'],
        ['label' => 'Profil', 'route' => 'profile.index', 'icon' => 'user-circle'],
    ];
@endphp

<nav
    class="safe-bottom fixed inset-x-0 bottom-0 z-40 border-t border-neutral-200/70 bg-white/95 shadow-soft backdrop-blur-md"
    aria-label="Navigasi utama"
>
    <div class="mx-auto flex max-w-md items-stretch justify-around">
        @foreach ($navItems as $item)
            @php $active = request()->routeIs($item['route']); @endphp
            <a
                href="{{ route($item['route']) }}"
                class="flex min-h-[56px] min-w-[64px] flex-1 flex-col items-center justify-center gap-0.5 text-xs font-medium {{ $active ? 'text-brand-700' : 'text-neutral-500' }}"
            >
                @if ($item['route'] === 'cart.index')
                    <span class="relative" x-data>
                        @svg('heroicon-'.($active ? 's' : 'o').'-'.$item['icon'], 'h-6 w-6')
                        <span
                            x-show="$store.cart.totalCount() > 0"
                            x-text="$store.cart.totalCount()"
                            x-cloak
                            class="absolute -right-2 -top-1 grid h-4 min-w-[16px] place-items-center rounded-full bg-rose-500 px-1 text-[10px] font-bold text-white"
                        ></span>
                    </span>
                @else
                    @svg('heroicon-'.($active ? 's' : 'o').'-'.$item['icon'], 'h-6 w-6')
                @endif
                {{ $item['label'] }}
            </a>
        @endforeach
    </div>
</nav>
