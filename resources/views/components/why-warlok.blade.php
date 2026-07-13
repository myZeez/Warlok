@php
    $points = [
        [
            'icon' => 'heroicon-o-chat-bubble-left-right',
            'title' => 'Langsung ke WhatsApp pemilik',
            'description' => 'Tiap toko dan produk terhubung ke nomor WhatsApp asli pemiliknya. Tanya stok, nego, atau pesan — tanpa akun, tanpa perantara.',
        ],
        [
            'icon' => 'heroicon-o-qr-code',
            'title' => 'QRIS tersedia di halaman toko',
            'description' => 'Kalau UMKM-nya sudah pasang QRIS, kode pembayarannya kelihatan langsung di halaman toko. Bayar tetap urusan kamu berdua, bukan lewat Warlok.',
        ],
        [
            'icon' => 'heroicon-o-map-pin',
            'title' => 'Khusus wilayahmu, bukan pasar nasional',
            'description' => 'Katalog difilter per kelurahan/kecamatan. Yang muncul cuma warung dan UMKM yang benar-benar bisa kamu datangi atau ambil sendiri.',
        ],
    ];
@endphp

<section x-data="{ revealed: false }" x-intersect.once="revealed = true" class="space-y-6">
    <div class="max-w-md">
        <h2 class="text-lg font-bold text-neutral-900">Kenapa Warlok</h2>
        <p class="mt-1 text-sm text-neutral-500">Bukan marketplace nasional yang dikecilkan — dari awal memang dibuat untuk satu wilayah.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        @foreach ($points as $index => $point)
            <div
                :class="revealed ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                class="rounded-2xl bg-white p-5 shadow-soft transition-all duration-700 ease-[cubic-bezier(0.25,1,0.5,1)] motion-reduce:transition-none"
                style="transition-delay: {{ $index * 100 }}ms"
            >
                <span class="grid h-11 w-11 place-items-center rounded-full bg-brand-50 text-brand-700">
                    @svg($point['icon'], 'h-6 w-6')
                </span>
                <p class="mt-4 font-bold text-neutral-900">{{ $point['title'] }}</p>
                <p class="mt-1.5 text-sm leading-relaxed text-neutral-500">{{ $point['description'] }}</p>
            </div>
        @endforeach
    </div>
</section>
