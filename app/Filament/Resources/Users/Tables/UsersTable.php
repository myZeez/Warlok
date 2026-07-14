<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role')
                    ->label('Peran')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => $state === 'admin' ? 'Admin' : 'Pemilik UMKM')
                    ->color(fn (string $state) => $state === 'admin' ? 'warning' : 'primary'),
                TextColumn::make('phone')
                    ->label('No. Telepon'),
                TextColumn::make('umkm.name')
                    ->label('Toko')
                    ->placeholder('—'),
                TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y'),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Peran')
                    ->options([
                        'admin' => 'Admin',
                        'umkm_owner' => 'Pemilik UMKM',
                    ]),
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
