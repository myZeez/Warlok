<x-layouts.app title="Kontak & Bantuan">
    <div class="mx-auto max-w-xl space-y-6 px-4 py-6">
        <div class="text-center">
            <h1 class="text-xl font-extrabold tracking-tight text-neutral-900">Kontak &amp; Bantuan</h1>
            <p class="mt-1 text-sm text-neutral-500">Ada pertanyaan seputar Warlok? Hubungi kami lewat salah satu cara berikut.</p>
        </div>

        <div class="space-y-3">
            <a href="https://wa.me/6281200000000" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 rounded-2xl bg-white p-4 shadow-soft transition hover:-translate-y-0.5 hover:shadow-soft-hover">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-whatsapp/10 text-whatsapp">
                    @svg('heroicon-o-chat-bubble-left-right', 'h-5 w-5')
                </span>
                <div>
                    <p class="font-semibold text-neutral-800">WhatsApp Admin</p>
                    <p class="text-sm text-neutral-500">+62 812-0000-0000</p>
                </div>
            </a>

            <a href="mailto:halo@warlok.id" class="flex items-center gap-3 rounded-2xl bg-white p-4 shadow-soft transition hover:-translate-y-0.5 hover:shadow-soft-hover">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-brand-50 text-brand-700">
                    @svg('heroicon-o-information-circle', 'h-5 w-5')
                </span>
                <div>
                    <p class="font-semibold text-neutral-800">Email</p>
                    <p class="text-sm text-neutral-500">halo@warlok.id</p>
                </div>
            </a>

            <div class="flex items-center gap-3 rounded-2xl bg-white p-4 shadow-soft">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-neutral-100 text-neutral-600">
                    @svg('heroicon-o-clock', 'h-5 w-5')
                </span>
                <div>
                    <p class="font-semibold text-neutral-800">Jam Layanan</p>
                    <p class="text-sm text-neutral-500">Senin&ndash;Sabtu, 09.00&ndash;17.00 WIB</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-neutral-100 p-4 text-sm text-neutral-500">
            <p><span class="font-semibold text-neutral-700">Catatan:</span> Warlok hanya membantu menemukan UMKM di sekitar kamu. Transaksi dan pembayaran dilakukan langsung antara pembeli dan pemilik UMKM lewat WhatsApp &amp; QRIS.</p>
        </div>
    </div>
</x-layouts.app>
