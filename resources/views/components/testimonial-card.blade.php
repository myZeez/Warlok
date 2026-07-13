@props(['name', 'role', 'quote'])

<div class="w-56 shrink-0 rounded-2xl bg-white p-4 shadow-soft">
    <div class="flex items-center gap-2.5">
        <div class="grid size-9 shrink-0 place-items-center rounded-full bg-brand-50 text-sm font-bold text-brand-700">
            {{ Str::of($name)->substr(0, 1)->upper() }}
        </div>
        <div class="min-w-0">
            <p class="truncate text-sm font-semibold text-neutral-800">{{ $name }}</p>
            <p class="truncate text-xs text-neutral-500">{{ $role }}</p>
        </div>
    </div>
    <blockquote class="mt-3 text-sm leading-relaxed text-neutral-600">&ldquo;{{ $quote }}&rdquo;</blockquote>
</div>
