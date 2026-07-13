<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function select(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'region_id' => ['nullable', 'exists:regions,id'],
        ]);

        if ($validated['region_id'] ?? null) {
            session(['region_id' => $validated['region_id']]);
        } else {
            session()->forget('region_id');
        }

        return back();
    }
}
