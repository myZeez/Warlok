<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Umkm;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    public function show(Umkm $umkm, Product $product): View
    {
        abort_unless($umkm->status === 'active', 404);
        abort_unless($product->is_active, 404);

        $product->load(['umkm.region', 'category', 'images']);

        $otherProducts = $umkm->products()
            ->active()
            ->where('id', '!=', $product->id)
            ->with('category')
            ->limit(4)
            ->get();

        return view('products.show', [
            'product' => $product,
            'otherProducts' => $otherProducts,
        ]);
    }
}
