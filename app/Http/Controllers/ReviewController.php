<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Umkm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function storeForUmkm(Request $request, Umkm $umkm): RedirectResponse
    {
        $umkm->reviews()->create($this->validated($request));

        return back()->with('status', 'Terima kasih atas ulasannya!');
    }

    public function storeForProduct(Request $request, Umkm $umkm, Product $product): RedirectResponse
    {
        $product->reviews()->create($this->validated($request));

        return back()->with('status', 'Terima kasih atas ulasannya!');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
