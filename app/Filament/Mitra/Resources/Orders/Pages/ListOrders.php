<?php

namespace App\Filament\Mitra\Resources\Orders\Pages;

use App\Filament\Mitra\Resources\Orders\OrderResource;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;
}
