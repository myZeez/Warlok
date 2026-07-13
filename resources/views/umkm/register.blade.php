<x-layouts.app title="Daftar UMKM">
    <div class="mx-auto max-w-xl space-y-5 px-4 py-6">
        <div>
            <h1 class="text-xl font-extrabold tracking-tight text-neutral-900">Daftarkan UMKM Kamu</h1>
            <p class="mt-1 text-sm text-neutral-500">
                Isi data di bawah ini. Tim kami akan meninjau sebelum toko kamu tampil di katalog Warlok.
            </p>
        </div>

        @if (session('status'))
            <div class="flex items-start gap-2 rounded-2xl bg-brand-50 p-4 text-sm text-brand-800">
                @svg('heroicon-o-check-circle', 'h-5 w-5 shrink-0 text-brand-600')
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('umkm.register.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 rounded-3xl bg-white p-5 shadow-soft">
            @csrf

            <div>
                <label for="name" class="mb-1 block text-sm font-semibold text-neutral-700">Nama UMKM</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required
                    class="w-full rounded-xl border border-neutral-200 px-4 py-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25">
                @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="region_id" class="mb-1 block text-sm font-semibold text-neutral-700">Wilayah</label>
                <select id="region_id" name="region_id" required
                    class="w-full rounded-xl border border-neutral-200 px-4 py-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25">
                    <option value="">Pilih wilayah</option>
                    @foreach ($regions as $region)
                        <option value="{{ $region->id }}" @selected(old('region_id') == $region->id)>
                            {{ $region->kelurahan }}, {{ $region->kecamatan }}
                        </option>
                    @endforeach
                </select>
                @error('region_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="mb-1 block text-sm font-semibold text-neutral-700">Deskripsi Singkat</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full rounded-xl border border-neutral-200 px-4 py-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25">{{ old('description') }}</textarea>
                @error('description') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="wa_number" class="mb-1 block text-sm font-semibold text-neutral-700">Nomor WhatsApp</label>
                <input id="wa_number" type="text" name="wa_number" value="{{ old('wa_number') }}" required placeholder="081234567890"
                    class="w-full rounded-xl border border-neutral-200 px-4 py-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25">
                @error('wa_number') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="address" class="mb-1 block text-sm font-semibold text-neutral-700">Alamat Lengkap</label>
                <textarea id="address" name="address" rows="2" required
                    class="w-full rounded-xl border border-neutral-200 px-4 py-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25">{{ old('address') }}</textarea>
                @error('address') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="logo" class="mb-1 block text-sm font-semibold text-neutral-700">Logo/Foto Toko</label>
                    <input id="logo" type="file" name="logo" accept="image/*"
                        class="w-full rounded-xl border border-neutral-200 px-3 py-2 text-xs file:mr-2 file:rounded-full file:border-0 file:bg-brand-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-brand-700">
                    @error('logo') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="qris" class="mb-1 block text-sm font-semibold text-neutral-700">Gambar QRIS</label>
                    <input id="qris" type="file" name="qris" accept="image/*"
                        class="w-full rounded-xl border border-neutral-200 px-3 py-2 text-xs file:mr-2 file:rounded-full file:border-0 file:bg-brand-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-brand-700">
                    @error('qris') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <button type="submit"
                class="flex min-h-[44px] w-full items-center justify-center rounded-full bg-brand-600 font-bold text-white hover:bg-brand-700">
                Kirim Pendaftaran
            </button>
        </form>
    </div>
</x-layouts.app>
