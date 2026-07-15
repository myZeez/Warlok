<?php

namespace App\Filament\Support;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductGallerySync
{
    public static function hydrate(Product $record): array
    {
        return $record->images()->pluck('image_path')->all();
    }

    public static function sync(Product $record, array $paths): void
    {
        $paths = array_values($paths);
        $existing = $record->images()->get();

        foreach ($existing->whereNotIn('image_path', $paths) as $removed) {
            Storage::disk('public')->delete($removed->image_path);
            $removed->delete();
        }

        $kept = $existing->pluck('image_path')->all();

        foreach ($paths as $index => $path) {
            if (in_array($path, $kept, true)) {
                $record->images()->where('image_path', $path)->update(['sort_order' => $index]);
            } else {
                $record->images()->create(['image_path' => $path, 'sort_order' => $index]);
            }
        }
    }
}
