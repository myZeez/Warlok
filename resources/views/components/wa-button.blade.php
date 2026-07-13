@props(['href', 'label' => 'Pesan via WhatsApp'])

<a
    href="{{ $href }}"
    target="_blank"
    rel="noopener noreferrer"
    {{ $attributes->merge(['class' => 'flex min-h-[44px] items-center justify-center gap-2 rounded-full bg-whatsapp px-5 py-3 font-bold text-neutral-900 transition hover:brightness-95 active:scale-[0.98]']) }}
>
    @svg('heroicon-s-chat-bubble-left-right', 'h-5 w-5')
    {{ $label }}
</a>
