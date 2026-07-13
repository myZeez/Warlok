<?php

namespace App\Filament\Resources\Regions;

use App\Filament\Resources\Regions\Pages\ManageRegions;
use App\Models\Region;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
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
                TextInput::make('kecamatan')
                    ->required()
                    ->maxLength(255),
                TextInput::make('kelurahan')
                    ->required()
                    ->maxLength(255),
                TextInput::make('kota')
                    ->required()
                    ->maxLength(255),
            ]);
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
                EditAction::make(),
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
