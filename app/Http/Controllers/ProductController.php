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

        $product->load(['umkm.region', 'category', 'images', 'reviews']);

        $otherProducts = $umkm->products()
            ->active()
            ->where('id', '!=', $product->id)
            ->with(['category', 'reviews'])
            ->limit(4)
            ->get();

        return view('products.show', [
            'product' => $product,
            'otherProducts' => $otherProducts,
        ]);
    }

    public function buyNow(Umkm $umkm, Product $product): View
    {
        abort_unless($umkm->status === 'active', 404);
        abort_unless($product->is_active, 404);
        abort_if($product->stock_status === 'habis', 404);

        return view('products.buy-now', [
            'product' => $product,
            'umkm' => $umkm,
            'methods' => $umkm->enabledDeliveryMethods(),
        ]);
    }
}
