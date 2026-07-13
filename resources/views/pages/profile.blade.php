@php
    $menuItems = [
        ['label' => 'Favorit Saya', 'route' => 'favorites.index', 'icon' => 'heart'],
        ['label' => 'Cara Kerja Warlok', 'route' => 'about', 'icon' => 'information-circle'],
        ['label' => 'Daftarkan UMKM Kamu', 'route' => 'umkm.register', 'icon' => 'building-storefront'],
        ['label' => 'Kontak & Bantuan', 'route' => 'contact', 'icon' => 'chat-bubble-left-right'],
    ];
@endphp

<x-layouts.app title="Profil">
    <div class="mx-auto max-w-xl space-y-6 px-4 py-6">
        <div class="flex items-center gap-3 rounded-3xl bg-white p-5 shadow-soft">
            <span class="grid h-14 w-14 place-items-center rounded-full bg-brand-600 text-xl font-extrabold text-white">W</span>
            <div>
                <p class="font-bold text-neutral-900">Halo, Tetangga!</p>
                <p class="text-sm text-neutral-500">Belum perlu akun untuk belanja di Warlok.</p>
            </div>
        </div>

        <div class="divide-y divide-neutral-100 overflow-hidden rounded-3xl bg-white shadow-soft">
            @foreach ($menuItems as $item)
                <a href="{{ route($item['route']) }}" class="flex items-center gap-3 p-4 hover:bg-neutral-50">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-brand-50 text-brand-700">
                        @svg('heroicon-o-'.$item['icon'], 'h-5 w-5')
                    </span>
                    <span class="flex-1 font-semibold text-neutral-700">{{ $item['label'] }}</span>
                    @svg('heroicon-o-chevron-right', 'h-5 w-5 text-neutral-300')
                </a>
            @endforeach
        </div>

        <p class="text-center text-xs text-neutral-400">Warlok &mdash; Warung Lokal Dekat Kamu</p>
    </div>
</x-layouts.app>
