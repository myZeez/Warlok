<div
    wire:ignore
    x-data="locationPicker(@js($getRecord()->lat), @js($getRecord()->long))"
    x-init="init()"
>
    <div class="mb-2 flex items-center justify-between">
        <span class="text-xs text-gray-500 dark:text-gray-400">Klik peta untuk menandai lokasi tokomu.</span>
        <button type="button" @click="useMyLocation()" class="text-xs font-semibold text-primary-600 hover:underline">
            Gunakan lokasi saya
        </button>
    </div>
    <div x-ref="map" style="height: 220px" class="w-full overflow-hidden rounded-lg border border-gray-300 dark:border-gray-600"></div>
</div>
