<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Umkm;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UmkmRegistrationController extends Controller
{
    public function create(): View
    {
        return view('umkm.register', [
            'regions' => Region::orderBy('kelurahan')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'region_id' => ['required', 'exists:regions,id'],
            'description' => ['nullable', 'string', 'max:1000'],
            'wa_number' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:1000'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'qris' => ['nullable', 'image', 'max:2048'],
        ]);

        $umkm = new Umkm([
            'region_id' => $validated['region_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'wa_number' => $validated['wa_number'],
            'address' => $validated['address'],
            'status' => 'pending',
        ]);

        if ($request->hasFile('logo')) {
            $umkm->logo_path = $request->file('logo')->store('umkm-logos', 'public');
        }

        if ($request->hasFile('qris')) {
            $umkm->qris_image_path = $request->file('qris')->store('umkm-qris', 'public');
        }

        $umkm->save();

        return redirect()
            ->route('umkm.register')
            ->with('status', 'Pendaftaran berhasil! Tim kami akan meninjau data UMKM kamu sebelum tampil di katalog.');
    }
}
