<?php

namespace App\Filament\Mitra\Resources\Orders\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('buyer_name')
                    ->label('Pembeli')
                    ->searchable(),
                TextColumn::make('buyer_phone')
                    ->label('Telepon'),
                TextColumn::make('delivery_method')
                    ->label('Pengiriman')
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'pickup' => 'Ambil di Toko',
                        'self_delivery' => 'Antar Sendiri',
                        'gojek' => 'Gojek',
                        'grab' => 'Grab',
                    }),
                TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Balas')
                    ->modalHeading('Update Status & Catatan untuk Pembeli')
                    ->schema([
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),
                        Textarea::make('seller_note')
                            ->label('Catatan untuk Pembeli')
                            ->rows(3)
                            ->placeholder('Contoh: pesanan sedang disiapkan, siap diambil jam 5 sore'),
                    ]),
                ViewAction::make()
                    ->schema([
                        Section::make('Pesanan')
                            ->columns(2)
                            ->components([
                                TextEntry::make('status')->badge(),
                                TextEntry::make('buyer_name')->label('Pembeli'),
                                TextEntry::make('buyer_phone')->label('Telepon'),
                                TextEntry::make('buyer_address')->label('Alamat')->placeholder('—')->columnSpan(2),
                                TextEntry::make('delivery_method')
                                    ->label('Pengiriman')
                                    ->formatStateUsing(fn (string $state) => match ($state) {
                                        'pickup' => 'Ambil di Toko',
                                        'self_delivery' => 'Antar Sendiri',
                                        'gojek' => 'Gojek',
                                        'grab' => 'Grab',
                                    }),
                                TextEntry::make('delivery_fee')->label('Biaya Kirim')->money('IDR'),
                                TextEntry::make('subtotal')->money('IDR'),
                                TextEntry::make('total')->money('IDR'),
                                TextEntry::make('notes')->label('Catatan Pembeli')->placeholder('—')->columnSpan(2),
                            ]),
                        Section::make('Produk')
                            ->components([
                                RepeatableEntry::make('items')
                                    ->label('')
                                    ->schema([
                                        TextEntry::make('product_name')->label('Produk'),
                                        TextEntry::make('quantity')->label('Jumlah'),
                                        TextEntry::make('price')->label('Harga')->money('IDR'),
                                        TextEntry::make('subtotal')->money('IDR'),
                                    ])
                                    ->columns(4),
                            ]),
                    ]),
            ]);
    }
}
