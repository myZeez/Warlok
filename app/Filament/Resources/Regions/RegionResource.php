<?php

namespace App\Filament\Resources\Regions;

use App\Filament\Resources\Regions\Pages\ManageRegions;
use App\Models\Region;
use App\Models\WilayahDistrict;
use App\Models\WilayahProvince;
use App\Models\WilayahRegency;
use App\Models\WilayahVillage;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RegionResource extends Resource
{
    protected static ?string $model = Region::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static ?string $navigationLabel = 'Wilayah';

    protected static ?string $modelLabel = 'Wilayah';

    protected static ?string $pluralModelLabel = 'Wilayah';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('wilayah_province_id')
                    ->label('Provinsi')
                    ->options(fn () => WilayahProvince::query()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->dehydrated(false)
                    ->afterStateUpdated(function (Set $set) {
                        $set('wilayah_regency_id', null);
                        $set('wilayah_district_id', null);
                        $set('wilayah_village_id', null);
                    })
                    ->required(),
                Select::make('wilayah_regency_id')
                    ->label('Kota/Kabupaten')
                    ->options(fn (Get $get) => WilayahRegency::query()
                        ->where('province_id', $get('wilayah_province_id'))
                        ->orderBy('name')
                        ->pluck('name', 'id'))
                    ->searchable()
                    ->live()
                    ->dehydrated(false)
                    ->disabled(fn (Get $get) => blank($get('wilayah_province_id')))
                    ->afterStateUpdated(function (Set $set) {
                        $set('wilayah_district_id', null);
                        $set('wilayah_village_id', null);
                    })
                    ->required(),
                Select::make('wilayah_district_id')
                    ->label('Kecamatan')
                    ->options(fn (Get $get) => WilayahDistrict::query()
                        ->where('regency_id', $get('wilayah_regency_id'))
                        ->orderBy('name')
                        ->pluck('name', 'id'))
                    ->searchable()
                    ->live()
                    ->dehydrated(false)
                    ->disabled(fn (Get $get) => blank($get('wilayah_regency_id')))
                    ->afterStateUpdated(fn (Set $set) => $set('wilayah_village_id', null))
                    ->required(),
                Select::make('wilayah_village_id')
                    ->label('Kelurahan/Desa')
                    ->options(fn (Get $get) => WilayahVillage::query()
                        ->where('district_id', $get('wilayah_district_id'))
                        ->orderBy('name')
                        ->pluck('name', 'id'))
                    ->searchable()
                    ->disabled(fn (Get $get) => blank($get('wilayah_district_id')))
                    ->required(),
            ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function withDenormalizedNames(array $data): array
    {
        $village = WilayahVillage::with('district.regency.province')->findOrFail($data['wilayah_village_id']);

        return [...$data, ...Region::attributesFromVillage($village)];
    }

    /**
     * Reconstruct the virtual ancestor selects (province/regency/district) from the
     * persisted wilayah_village_id, so editing an existing region pre-fills the cascade.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function withAncestorIds(array $data): array
    {
        if (blank($data['wilayah_village_id'] ?? null)) {
            return $data;
        }

        $village = WilayahVillage::with('district.regency')->find($data['wilayah_village_id']);

        if (! $village) {
            return $data;
        }

        return [
            ...$data,
            'wilayah_district_id' => $village->district_id,
            'wilayah_regency_id' => $village->district->regency_id,
            'wilayah_province_id' => $village->district->regency->province_id,
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('umkms'))
            ->columns([
                TextColumn::make('kelurahan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kecamatan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kota')
                    ->searchable(),
                TextColumn::make('umkms_count')
                    ->label('Jumlah UMKM')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->mutateRecordDataUsing(fn (array $data) => static::withAncestorIds($data))
                    ->mutateFormDataUsing(fn (array $data) => static::withDenormalizedNames($data)),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageRegions::route('/'),
        ];
    }
}
