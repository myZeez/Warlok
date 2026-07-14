<?php

namespace App\Filament\Mitra\Resources\Products\Pages;

use App\Filament\Mitra\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['umkm_id'] = auth()->user()->umkm->id;

        return $data;
    }
}
