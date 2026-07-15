<?php

namespace App\Filament\Support;

use App\Models\Product;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;

class ProductDeletionGuard
{
    public static function forSingle(): callable
    {
        return function (Action $action, Product $record) {
            if ($record->orderItems()->exists()) {
                static::notify();
                $action->halt();
            }
        };
    }

    public static function forBulk(): callable
    {
        return function (Action $action, Collection $records) {
            if ($records->contains(fn (Product $record) => $record->orderItems()->exists())) {
                static::notify();
                $action->halt();
            }
        };
    }

    protected static function notify(): void
    {
        Notification::make()
            ->danger()
            ->title('Produk ini punya riwayat pesanan')
            ->body('Nonaktifkan produk lewat toggle "Aktif" saja, jangan dihapus -- menghapusnya akan merusak riwayat pesanan yang sudah ada.')
            ->send();
    }
}
