<?php

namespace App\Filament\Mitra\Resources\Products;

use App\Filament\Mitra\Resources\Products\Pages\CreateProduct;
use App\Filament\Mitra\Resources\Products\Pages\EditProduct;
use App\Filament\Mitra\Resources\Products\Pages\ListProducts;
use App\Filament\Mitra\Resources\Products\Schemas\ProductForm;
use App\Filament\Mitra\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static ?string $navigationLabel = 'Produk';

    protected static ?string $modelLabel = 'Produk';

    protected static ?string $pluralModelLabel = 'Produk';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('umkm_id', auth()->user()->umkm?->id ?? 0);
    }

    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
