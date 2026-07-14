<?php

namespace App\Filament\Resources\Umkms\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $title = 'Produk';

    // No Policy classes exist in this app (access is gated entirely at the panel level via
    // User::canAccessPanel()) -- Gate::inspect() denies by default with no policy registered,
    // so authorization must be skipped outright rather than just relaxing the existence check.
    protected static bool $shouldSkipAuthorization = true;

    protected static bool $isLazy = false;

    public function form(Schema $schema): Schema
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
                        modifyRuleUsing: fn ($rule) => $rule->where('umkm_id', $this->getOwnerRecord()->id),
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('photo_path')
                    ->label('')
                    ->disk('public')
                    ->square(),
                TextColumn::make('name')
                    ->label('Produk')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Kategori'),
                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                IconColumn::make('stock_status')
                    ->label('Stok')
                    ->icon(fn (string $state) => $state === 'tersedia' ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn (string $state) => $state === 'tersedia' ? 'success' : 'danger'),
                ToggleColumn::make('is_active')
                    ->label('Aktif'),
            ])
            ->headerActions([
                CreateAction::make(),
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
}
