<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Region;
use App\Models\Umkm;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WarlokStatsOverview extends StatsOverviewWidget
{
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        return [
            Stat::make('UMKM Aktif', Umkm::where('status', 'active')->count())
                ->description(Umkm::where('status', 'pending')->count().' menunggu persetujuan')
                ->color('success'),
            Stat::make('Total Produk', Product::where('is_active', true)->count())
                ->color('warning'),
            Stat::make('Wilayah Tercakup', Region::count())
                ->color('primary'),
        ];
    }
}
