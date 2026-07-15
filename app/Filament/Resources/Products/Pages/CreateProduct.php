<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Filament\Support\HasProductGallery;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    use HasProductGallery;

    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->stashGallery($data);
    }

    protected function afterCreate(): void
    {
        $this->syncGallery();
    }
}
