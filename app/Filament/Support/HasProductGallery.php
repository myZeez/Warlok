<?php

namespace App\Filament\Support;

trait HasProductGallery
{
    // afterCreate()/afterSave() run via Filament's callHook(), which calls the method
    // with zero arguments -- $data from mutateFormDataBefore{Create,Save} isn't reachable
    // there any other way, so it has to be stashed on the page instance in between.
    protected array $pendingGallery = [];

    protected function stashGallery(array $data): array
    {
        $this->pendingGallery = $data['gallery'] ?? [];

        return $data;
    }

    protected function syncGallery(): void
    {
        ProductGallerySync::sync($this->record, $this->pendingGallery);
    }
}
