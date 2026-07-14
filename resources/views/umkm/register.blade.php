<x-layouts.app title="Daftar UMKM">
    <div class="mx-auto max-w-xl space-y-5 px-4 py-6">
        <div>
            <x-page-logo />

            <h1 class="text-xl font-extrabold tracking-tight text-neutral-900">Daftarkan UMKM Kamu</h1>
            <p class="mt-1 text-sm text-neutral-500">
                Isi data di bawah ini. Akun kamu langsung aktif setelah daftar, jadi kamu bisa mulai tambah produk sambil menunggu tim kami meninjau tokonya.
            </p>
        </div>

        @if (session('status'))
            <div class="flex items-start gap-2 rounded-2xl bg-brand-50 p-4 text-sm text-brand-800">
                @svg('heroicon-o-check-circle', 'h-5 w-5 shrink-0 text-brand-600')
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('umkm.register.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 rounded-3xl bg-white p-5 shadow-soft">
            @csrf

            <div class="space-y-4">
                <h2 class="text-sm font-bold text-neutral-900">Akun Pemilik</h2>

                <div>
                    <label for="owner_name" class="mb-1 block text-sm font-semibold text-neutral-700">Nama Kamu</label>
                    <input id="owner_name" type="text" name="owner_name" value="{{ old('owner_name') }}" required
                        class="w-full rounded-xl border border-neutral-200 px-4 py-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25">
                    @error('owner_name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="mb-1 block text-sm font-semibold text-neutral-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="w-full rounded-xl border border-neutral-200 px-4 py-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25">
                    @error('email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="password" class="mb-1 block text-sm font-semibold text-neutral-700">Kata Sandi</label>
                        <input id="password" type="password" name="password" required
                            class="w-full rounded-xl border border-neutral-200 px-4 py-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25">
                        @error('password') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="mb-1 block text-sm font-semibold text-neutral-700">Ulangi Kata Sandi</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="w-full rounded-xl border border-neutral-200 px-4 py-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25">
                    </div>
                </div>
            </div>

            <div class="space-y-4 border-t border-neutral-100 pt-5">
                <h2 class="text-sm font-bold text-neutral-900">Profil UMKM</h2>

                <div>
                    <label for="name" class="mb-1 block text-sm font-semibold text-neutral-700">Nama UMKM</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required
                        class="w-full rounded-xl border border-neutral-200 px-4 py-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25">
                    @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div
                    x-data="wilayahCascade({
                        provinsi: '{{ old('provinsi_id') }}',
                        kabupaten: '{{ old('kabupaten_id') }}',
                        kecamatan: '{{ old('kecamatan_id') }}',
                        kelurahan: '{{ old('kelurahan_id') }}',
                    })"
                    x-init="init()"
                >
                    <p class="mb-1 text-sm font-semibold text-neutral-700">Wilayah</p>
                    <noscript>
                        <p class="mb-2 rounded-lg bg-amber-50 px-3 py-2 text-xs text-amber-800">Aktifkan JavaScript untuk memilih wilayah.</p>
                    </noscript>
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <select name="provinsi_id" x-model="selected.provinsi" @change="onProvinsiChange()" required
                            class="w-full rounded-xl border border-neutral-200 px-4 py-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25">
                            <option value="">Pilih Provinsi</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
                            @endforeach
                        </select>

                        <select name="kabupaten_id" x-model="selected.kabupaten" @change="onKabupatenChange()"
                            :disabled="!selected.provinsi" required
                            class="w-full rounded-xl border border-neutral-200 px-4 py-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25 disabled:bg-neutral-50 disabled:text-neutral-400">
                            <option value="">Pilih Kota/Kabupaten</option>
                            <template x-for="option in kabupatenOptions" :key="option.id">
                                <option :value="option.id" x-text="option.name" :selected="option.id === selected.kabupaten"></option>
                            </template>
                        </select>

                        <select name="kecamatan_id" x-model="selected.kecamatan" @change="onKecamatanChange()"
                            :disabled="!selected.kabupaten" required
                            class="w-full rounded-xl border border-neutral-200 px-4 py-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25 disabled:bg-neutral-50 disabled:text-neutral-400">
                            <option value="">Pilih Kecamatan</option>
                            <template x-for="option in kecamatanOptions" :key="option.id">
                                <option :value="option.id" x-text="option.name" :selected="option.id === selected.kecamatan"></option>
                            </template>
                        </select>

                        <select name="kelurahan_id" x-model="selected.kelurahan"
                            :disabled="!selected.kecamatan" required
                            class="w-full rounded-xl border border-neutral-200 px-4 py-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25 disabled:bg-neutral-50 disabled:text-neutral-400">
                            <option value="">Pilih Kelurahan/Desa</option>
                            <template x-for="option in kelurahanOptions" :key="option.id">
                                <option :value="option.id" x-text="option.name" :selected="option.id === selected.kelurahan"></option>
                            </template>
                        </select>
                    </div>
                    @error('provinsi_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    @error('kabupaten_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    @error('kecamatan_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    @error('kelurahan_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
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
            </div>

            <button type="submit"
                class="flex min-h-[44px] w-full items-center justify-center rounded-full bg-brand-600 font-bold text-white hover:bg-brand-700">
                Kirim Pendaftaran
            </button>
        </form>
    </div>
</x-layouts.app>
