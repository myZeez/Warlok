@php
    $testimonials = [
        ['name' => 'Rina', 'role' => 'Pembeli · Dago', 'quote' => 'Enak banget, tinggal pilih wilayah terus langsung ketemu warung deket rumah. Pesennya tinggal chat WA.'],
        ['name' => 'Dimas', 'role' => 'Pembeli · Sekeloa', 'quote' => 'Baru tau ternyata ada laundry sama bengkel deket kosan. Selama ini gak sadar aja.'],
        ['name' => 'Bu Yuli', 'role' => 'Pemilik UMKM · Lebak Siliwangi', 'quote' => 'Daftarnya gampang, sehari udah di-approve admin. Tetangga yang belum pernah belanja jadi tau warung saya.'],
        ['name' => 'Fajar', 'role' => 'Pembeli · Dago', 'quote' => 'Suka fitur filter kategori sama urutan harga, jadi gak perlu scroll kebanyakan buat cari jajanan murah.'],
        ['name' => 'Pak Herman', 'role' => 'Pemilik UMKM · Sekeloa', 'quote' => 'QRIS langsung kelihatan di halaman toko, jadi pembeli gak perlu tanya-tanya lagi soal pembayaran.'],
        ['name' => 'Sari', 'role' => 'Pembeli · Lebak Siliwangi', 'quote' => 'Tampilannya familiar, kayak Shopee tapi khusus tetangga sekitar. Gampang dipakai dari awal.'],
        ['name' => 'Bu Wati', 'role' => 'Pemilik UMKM · Dago', 'quote' => 'Awalnya ragu perlu ribet setup toko online, ternyata cuma isi form sekali doang.'],
        ['name' => 'Andi', 'role' => 'Pembeli · Sekeloa', 'quote' => 'Jarak ke toko kelihatan di setiap produk, jadi bisa milih yang paling deket buat jalan kaki.'],
        ['name' => 'Tika', 'role' => 'Pembeli · Dago', 'quote' => 'Chat WA langsung connect ke pemilik toko, gak perlu daftar akun dulu buat mulai pesan.'],
    ];
@endphp

<section
    x-data="{ revealed: false }"
    x-intersect.once="revealed = true"
    :class="revealed ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
    class="space-y-3 transition-all duration-700 ease-[cubic-bezier(0.25,1,0.5,1)] motion-reduce:transition-none"
>
    <div class="text-center">
        <h2 class="text-lg font-bold text-neutral-900">Kata Tetangga Sekitar</h2>
        <p class="mx-auto mt-1 max-w-md text-sm text-neutral-500">
            Cerita dari pembeli dan pemilik UMKM yang sudah coba Warlok di masa uji coba awal.
        </p>
    </div>

    <div class="relative left-1/2 w-screen -translate-x-1/2 bg-paper">
        <div class="relative mx-auto h-80 max-w-5xl overflow-hidden [perspective:500px] sm:h-96 lg:h-[26rem]">
            <div
                class="absolute left-1/2 top-1/2 flex flex-row items-center gap-4 [--reveal-scale:0.72] [--reveal-rotate-y:-5deg] sm:[--reveal-scale:0.9] sm:[--reveal-rotate-y:-8deg] md:[--reveal-scale:1] md:[--reveal-rotate-y:-10deg]"
                style="transform: translate(-50%, -50%) scale(var(--reveal-scale)) translateZ(-100px) rotateX(20deg) rotateY(var(--reveal-rotate-y)) rotateZ(20deg);"
            >
                <x-marquee vertical pause-on-hover :repeat="3" class="[--duration:35s]" aria-label="Ulasan pembeli dan UMKM Warlok">
                    @foreach ($testimonials as $t)
                        <x-testimonial-card :name="$t['name']" :role="$t['role']" :quote="$t['quote']" />
                    @endforeach
                </x-marquee>
                <x-marquee vertical pause-on-hover reverse :repeat="3" class="[--duration:35s]">
                    @foreach ($testimonials as $t)
                        <x-testimonial-card :name="$t['name']" :role="$t['role']" :quote="$t['quote']" />
                    @endforeach
                </x-marquee>
                <x-marquee vertical pause-on-hover :repeat="3" class="[--duration:35s]">
                    @foreach ($testimonials as $t)
                        <x-testimonial-card :name="$t['name']" :role="$t['role']" :quote="$t['quote']" />
                    @endforeach
                </x-marquee>
                <x-marquee vertical pause-on-hover reverse :repeat="3" class="[--duration:35s]">
                    @foreach ($testimonials as $t)
                        <x-testimonial-card :name="$t['name']" :role="$t['role']" :quote="$t['quote']" />
                    @endforeach
                </x-marquee>
                <div class="hidden lg:contents">
                    <x-marquee vertical pause-on-hover :repeat="3" class="[--duration:35s]">
                        @foreach ($testimonials as $t)
                            <x-testimonial-card :name="$t['name']" :role="$t['role']" :quote="$t['quote']" />
                        @endforeach
                    </x-marquee>
                    <x-marquee vertical pause-on-hover reverse :repeat="3" class="[--duration:35s]">
                        @foreach ($testimonials as $t)
                            <x-testimonial-card :name="$t['name']" :role="$t['role']" :quote="$t['quote']" />
                        @endforeach
                    </x-marquee>
                </div>
            </div>

            <div class="pointer-events-none absolute inset-x-0 top-0 h-1/2 bg-gradient-to-b from-paper to-transparent"></div>
            <div class="pointer-events-none absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-paper to-transparent"></div>
            <div class="pointer-events-none absolute inset-y-0 left-0 w-1/5 bg-gradient-to-r from-paper to-transparent"></div>
            <div class="pointer-events-none absolute inset-y-0 right-0 w-1/5 bg-gradient-to-l from-paper to-transparent"></div>
        </div>
    </div>

    <p class="text-center text-xs text-neutral-400">Cerita dari fase uji coba awal &mdash; nama disamarkan.</p>
</section>
