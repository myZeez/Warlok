@php
    $currentRegion = session('region_id')
        ? \App\Models\Region::find(session('region_id'))
        : null;
@endphp

<header class="sticky top-0 z-40 border-b border-neutral-200/70 bg-white/90 backdrop-blur-md">
    <div class="mx-auto flex max-w-6xl items-center gap-3 px-4 py-3">
        <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2">
            <span class="grid h-9 w-9 place-items-center rounded-xl bg-brand-600 text-base font-extrabold text-white">W</span>
            <span class="text-lg font-extrabold tracking-tight text-neutral-900">Warlok</span>
        </a>

        <form action="{{ route('region.select') }}" method="POST" class="min-w-0 flex-1">
            @csrf
            <div class="relative">
                <select
                    name="region_id"
                    onchange="this.form.submit()"
                    class="w-full appearance-none truncate rounded-full border border-neutral-200 bg-neutral-50 py-2 pl-9 pr-8 text-sm font-medium text-neutral-700 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25"
                    aria-label="Pilih wilayah"
                >
                    <option value="">Semua Wilayah</option>
                    @foreach (\App\Models\Region::orderBy('kelurahan')->get() as $region)
                        <option value="{{ $region->id }}" @selected($currentRegion?->id === $region->id)>
                            {{ $region->kelurahan }}, {{ $region->kecamatan }}
                        </option>
                    @endforeach
                </select>
                @svg('heroicon-o-map-pin', 'pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-brand-600')
                @svg('heroicon-o-chevron-down', 'pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-neutral-400')
            </div>
        </form>

        <a
            href="{{ route('catalog.index') }}"
            class="grid h-11 w-11 shrink-0 place-items-center rounded-full text-neutral-600 hover:bg-neutral-100"
            aria-label="Cari"
        >
            @svg('heroicon-o-magnifying-glass', 'h-5 w-5')
        </a>
    </div>
</header>
