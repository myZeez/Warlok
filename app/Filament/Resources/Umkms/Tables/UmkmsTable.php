<?php

namespace App\Filament\Resources\Umkms\Tables;

use App\Models\Umkm;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UmkmsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('products'))
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('logo_path')
                    ->label('')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(fn (Umkm $record) => 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&background=15803d&color=fff'),
                TextColumn::make('name')
                    ->label('Nama UMKM')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('region.kelurahan')
                    ->label('Wilayah')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'active' => 'success',
                        'pending' => 'warning',
                        'inactive' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('products_count')
                    ->label('Produk')
                    ->badge(),
                ToggleColumn::make('is_open')
                    ->label('Buka'),
                TextColumn::make('wa_number')
                    ->label('No. WA'),
                TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
                SelectFilter::make('region_id')
                    ->label('Wilayah')
                    ->relationship('region', 'kelurahan'),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Setujui')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->visible(fn (Umkm $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (Umkm $record) {
                        $record->update(['status' => 'active']);
                        Notification::make()->title('UMKM disetujui')->success()->send();
                    }),
                Action::make('reject')
                    ->label('Tolak')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->color('danger')
                    ->visible(fn (Umkm $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (Umkm $record) {
                        $record->update(['status' => 'inactive']);
                        Notification::make()->title('UMKM ditolak')->warning()->send();
                    }),
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
