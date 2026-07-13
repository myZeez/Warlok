@props(['category'])

<a
    href="{{ route('categories.show', $category->slug) }}"
    class="flex min-w-[84px] flex-col items-center gap-2.5 rounded-2xl bg-white px-4 py-4 text-center shadow-soft transition hover:-translate-y-1 hover:shadow-soft-hover sm:min-w-[104px] sm:gap-3 sm:px-6 sm:py-5"
>
    <span class="grid h-12 w-12 place-items-center rounded-full bg-brand-50 text-brand-700 sm:h-14 sm:w-14">
        @svg($category->icon ?: 'heroicon-o-tag', 'h-6 w-6 sm:h-7 sm:w-7')
    </span>
    <span class="text-xs font-semibold text-neutral-700 sm:text-sm">{{ $category->name }}</span>
</a>
