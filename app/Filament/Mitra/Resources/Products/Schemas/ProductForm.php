<?php

namespace App\Filament\Mitra\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->label('Nama Produk')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(
                        ignoreRecord: true,
                        modifyRuleUsing: fn ($rule) => $rule->where('umkm_id', auth()->user()->umkm?->id),
                    ),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3),
                TextInput::make('price')
                    ->label('Harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                Select::make('stock_status')
                    ->label('Stok')
                    ->options([
                        'tersedia' => 'Tersedia',
                        'habis' => 'Habis',
                    ])
                    ->default('tersedia')
                    ->required(),
                Toggle::make('is_active')
                    ->label('Aktif ditampilkan')
                    ->default(true),
                FileUpload::make('photo_path')
                    ->label('Foto Produk')
                    ->image()
                    ->disk('public')
                    ->directory('product-photos'),
            ]);
    }
}
