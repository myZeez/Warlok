<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use Illuminate\Contracts\View\View;

class UmkmController extends Controller
{
    public function show(Umkm $umkm): View
    {
        abort_unless($umkm->status === 'active', 404);

        $umkm->load([
            'region',
            'products' => fn ($query) => $query->active()->with(['category', 'reviews']),
            'reviews',
        ]);

        return view('umkm.show', ['umkm' => $umkm]);
    }
}
