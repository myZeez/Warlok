@php
    $buyerSteps = [
        ['title' => 'Pilih wilayah kamu', 'description' => 'Buka halaman utama dan pilih kecamatan/kelurahan tempat kamu berada.'],
        ['title' => 'Jelajahi katalog', 'description' => 'Lihat UMKM dan produk di sekitar, atau cari & filter berdasarkan kategori.'],
        ['title' => 'Buka detail produk', 'description' => 'Cek foto, harga, deskripsi, dan jarak dari lokasi kamu.'],
        ['title' => 'Pesan via WhatsApp', 'description' => 'Klik tombol WhatsApp untuk chat langsung dengan pemilik UMKM, lalu bayar via QRIS yang tersedia di halaman toko.'],
    ];

    $umkmSteps = [
        ['title' => 'Isi formulir pendaftaran', 'description' => 'Lengkapi nama usaha, wilayah, nomor WhatsApp, dan alamat di halaman Daftar UMKM.'],
        ['title' => 'Tim kami meninjau', 'description' => 'Admin Warlok memeriksa kelengkapan data sebelum toko kamu tampil ke publik.'],
        ['title' => 'Toko tampil di katalog', 'description' => 'Setelah disetujui, pembeli di sekitar wilayah kamu bisa menemukan dan menghubungi toko kamu.'],
    ];
@endphp

<x-layouts.app title="Cara Kerja">
    <div class="mx-auto max-w-2xl space-y-10 px-4 py-6">
        <div class="text-center">
            <x-page-logo />

            <h1 class="text-xl font-extrabold tracking-tight text-neutral-900">Cara Kerja Warlok</h1>
            <p class="mt-1 text-sm text-neutral-500">
                Warlok menghubungkan kamu dengan warung dan UMKM di sekitar &mdash; transaksi tetap langsung lewat WhatsApp &amp; QRIS.
            </p>
        </div>

        <section class="space-y-4">
            <h2 class="text-lg font-bold text-neutral-900">Untuk Pembeli</h2>
            <ol class="space-y-4">
                @foreach ($buyerSteps as $index => $step)
                    <li class="flex gap-3">
                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-brand-600 text-sm font-bold text-white">
                            {{ $index + 1 }}
                        </span>
                        <div>
                            <p class="font-semibold text-neutral-800">{{ $step['title'] }}</p>
                            <p class="text-sm text-neutral-500">{{ $step['description'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </section>

        <section class="space-y-4">
            <h2 class="text-lg font-bold text-neutral-900">Untuk Pemilik UMKM</h2>
            <ol class="space-y-4">
                @foreach ($umkmSteps as $index => $step)
                    <li class="flex gap-3">
                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-neutral-900 text-sm font-bold text-white">
                            {{ $index + 1 }}
                        </span>
                        <div>
                            <p class="font-semibold text-neutral-800">{{ $step['title'] }}</p>
                            <p class="text-sm text-neutral-500">{{ $step['description'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
            <a
                href="{{ route('umkm.register') }}"
                class="flex min-h-[44px] items-center justify-center rounded-full bg-brand-600 px-6 font-bold text-white hover:bg-brand-700 sm:inline-flex"
            >
                Daftarkan UMKM Kamu
            </a>
        </section>
    </div>
</x-layouts.app>
