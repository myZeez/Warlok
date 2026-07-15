<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Umkm;
use App\Models\User;
use App\Models\WilayahProvince;
use App\Models\WilayahVillage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UmkmRegistrationController extends Controller
{
    public function create(): View
    {
        return view('umkm.register', [
            // Cache plain arrays, not Eloquent objects: this app's cache config
            // (serializable_classes => false) rejects unserializing objects from the
            // database cache store, silently corrupting anything object-shaped on read-back.
            'provinces' => Cache::rememberForever(
                'wilayah:provinces',
                fn () => WilayahProvince::query()->orderBy('name')->get(['id', 'name'])->toArray(),
            ),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'owner_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::default()],
            'name' => ['required', 'string', 'max:255'],
            'provinsi_id' => ['required', 'string', 'exists:wilayah_provinces,id'],
            'kabupaten_id' => ['required', 'string',
                Rule::exists('wilayah_regencies', 'id')->where('province_id', $request->input('provinsi_id'))],
            'kecamatan_id' => ['required', 'string',
                Rule::exists('wilayah_districts', 'id')->where('regency_id', $request->input('kabupaten_id'))],
            'kelurahan_id' => ['required', 'string',
                Rule::exists('wilayah_villages', 'id')->where('district_id', $request->input('kecamatan_id'))],
            'description' => ['nullable', 'string', 'max:1000'],
            'wa_number' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:1000'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'long' => ['nullable', 'numeric', 'between:-180,180'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'qris' => ['nullable', 'image', 'max:2048'],
        ]);

        $logoPath = $request->hasFile('logo') ? $request->file('logo')->store('umkm-logos', 'public') : null;
        $qrisPath = $request->hasFile('qris') ? $request->file('qris')->store('umkm-qris', 'public') : null;

        $user = DB::transaction(function () use ($validated, $logoPath, $qrisPath) {
            $village = WilayahVillage::findOrFail($validated['kelurahan_id']);
            $region = Region::fromWilayahVillage($village);

            $user = User::create([
                'name' => $validated['owner_name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => 'umkm_owner',
                'phone' => $validated['wa_number'],
            ]);

            Umkm::create([
                'user_id' => $user->id,
                'region_id' => $region->id,
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'wa_number' => $validated['wa_number'],
                'address' => $validated['address'],
                'lat' => $validated['lat'] ?? null,
                'long' => $validated['long'] ?? null,
                'status' => 'pending',
                'logo_path' => $logoPath,
                'qris_image_path' => $qrisPath,
            ]);

            return $user;
        });

        Auth::login($user);

        return redirect()
            ->to('/mitra')
            ->with('status', 'Pendaftaran berhasil! Toko kamu sedang ditinjau tim kami, tapi kamu sudah bisa mulai menambahkan produk sekarang.');
    }
}
