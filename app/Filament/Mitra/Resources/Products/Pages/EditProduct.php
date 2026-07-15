<?php

namespace App\Filament\Mitra\Resources\Products\Pages;

use App\Filament\Mitra\Resources\Products\ProductResource;
use App\Filament\Support\HasProductGallery;
use App\Filament\Support\ProductGallerySync;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    use HasProductGallery;

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['gallery'] = ProductGallerySync::hydrate($this->getRecord());

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->stashGallery($data);
    }

    protected function afterSave(): void
    {
        $this->syncGallery();
    }
}
