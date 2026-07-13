<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Region;
use App\Models\Umkm;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('products')->orderBy('name')->get();

        $featuredUmkms = Umkm::query()
            ->active()
            ->with('region')
            ->inRandomOrder()
            ->limit(6)
            ->get()
            ->map(fn (Umkm $umkm) => [
                'id' => $umkm->id,
                'name' => $umkm->name,
                'region' => $umkm->region->kelurahan,
                'logoUrl' => $umkm->logo_path ? Storage::url($umkm->logo_path) : null,
                'initial' => mb_strtoupper(mb_substr($umkm->name, 0, 1)),
                'isOpen' => $umkm->is_open,
                'url' => route('umkm.show', $umkm->slug),
            ])
            ->values();

        $featuredProducts = Product::query()
            ->active()
            ->where('stock_status', 'tersedia')
            ->with(['umkm', 'category'])
            ->inRandomOrder()
            ->limit(8)
            ->get();

        $stats = [
            'umkmCount' => Umkm::active()->count(),
            'productCount' => Product::active()->where('stock_status', 'tersedia')->count(),
            'regionCount' => Region::count(),
        ];

        return view('home', [
            'categories' => $categories,
            'featuredUmkms' => $featuredUmkms,
            'featuredProducts' => $featuredProducts,
            'stats' => $stats,
        ]);
    }
}
