@props([
    'subtitle' => 'Temukan produk dan jasa dari tetangga sekitar, lalu pesan langsung lewat WhatsApp.',
])

<section class="relative flex flex-col items-center justify-center overflow-hidden bg-paper px-4 pb-12 pt-16 sm:pt-20 lg:min-h-[86vh] lg:pb-20 lg:pt-24">
    <div
        class="pointer-events-none absolute left-1/2 top-0 h-[30rem] w-[54rem] -translate-x-1/2 -translate-y-1/4 rounded-full bg-brand-100/70 blur-3xl lg:h-[38rem] lg:w-[64rem]"
        aria-hidden="true"
    ></div>

    <div class="relative mx-auto flex max-w-2xl flex-col items-center gap-6 text-center lg:max-w-3xl lg:gap-8">
        <x-page-logo />

        <h1 class="text-balance text-[clamp(2.25rem,6vw,4.25rem)] font-extrabold leading-[1.02] tracking-[-0.03em] text-neutral-900">
            Warung &amp; UMKM <span class="text-brand-600">lokal</span>, deket kamu.
        </h1>
        <p class="max-w-md text-[15px] leading-relaxed text-neutral-500 sm:text-base lg:max-w-lg lg:text-lg">
            {{ $subtitle }}
        </p>

        <div class="mt-2 w-full max-w-xl">
            {{ $slot }}
        </div>
    </div>
</section>
