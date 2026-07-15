@props(['reviews', 'average', 'count', 'action'])

<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-bold text-neutral-900">Ulasan</h2>
        <x-star-rating :rating="$average" :count="$count" />
    </div>

    @if (session('status'))
        <div class="flex items-start gap-2 rounded-2xl bg-brand-50 p-3 text-sm text-brand-800">
            @svg('heroicon-o-check-circle', 'h-5 w-5 shrink-0 text-brand-600')
            {{ session('status') }}
        </div>
    @endif

    <details class="rounded-2xl bg-white p-4 shadow-soft" @if ($errors->hasAny(['customer_name', 'rating', 'comment'])) open @endif>
        <summary class="cursor-pointer text-sm font-semibold text-brand-700">Tulis Ulasan</summary>

        <form action="{{ $action }}" method="POST" class="mt-3 space-y-3">
            @csrf

            <div>
                <label class="mb-1 block text-xs font-semibold text-neutral-700">Nama</label>
                <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                    class="w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25">
                @error('customer_name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold text-neutral-700">Rating</label>
                <div class="flex gap-1" x-data="{ rating: {{ (int) old('rating', 5) }} }">
                    @for ($i = 1; $i <= 5; $i++)
                        <button type="button" @click="rating = {{ $i }}">
                            <span x-show="rating >= {{ $i }}">@svg('heroicon-s-star', 'h-6 w-6 text-amber-400')</span>
                            <span x-show="rating < {{ $i }}">@svg('heroicon-o-star', 'h-6 w-6 text-neutral-300')</span>
                        </button>
                    @endfor
                    <input type="hidden" name="rating" :value="rating">
                </div>
                @error('rating') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold text-neutral-700">Komentar (opsional)</label>
                <textarea name="comment" rows="2"
                    class="w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25">{{ old('comment') }}</textarea>
                @error('comment') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="rounded-full bg-brand-600 px-4 py-2 text-sm font-bold text-white hover:bg-brand-700">
                Kirim Ulasan
            </button>
        </form>
    </details>

    @if ($reviews->isEmpty())
        <p class="text-sm text-neutral-400">Belum ada ulasan.</p>
    @else
        <div class="space-y-3">
            @foreach ($reviews as $review)
                <div class="rounded-2xl bg-white p-4 shadow-soft">
                    <div class="flex items-center justify-between">
                        <p class="font-semibold text-neutral-800">{{ $review->customer_name }}</p>
                        <x-star-rating :rating="(float) $review->rating" size="h-3.5 w-3.5" />
                    </div>
                    @if ($review->comment)
                        <p class="mt-1 text-sm text-neutral-600">{{ $review->comment }}</p>
                    @endif
                    <p class="mt-1 text-xs text-neutral-400">{{ $review->created_at->diffForHumans() }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
