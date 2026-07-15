<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    protected static ?string $title = 'Ulasan';

    // No Policy classes exist in this app (access is gated entirely at the panel level via
    // User::canAccessPanel()) -- Gate::inspect() denies by default with no policy registered,
    // so authorization must be skipped outright rather than just relaxing the existence check.
    protected static bool $shouldSkipAuthorization = true;

    protected static bool $isLazy = false;

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('customer_name')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('customer_name')
                    ->label('Nama'),
                TextColumn::make('rating')
                    ->label('Rating')
                    ->badge(),
                TextColumn::make('comment')
                    ->label('Komentar')
                    ->limit(60)
                    ->placeholder('—'),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y'),
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
