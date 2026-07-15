<?php

namespace App\Filament\Resources\Umkms\Schemas;

use App\Models\Region;
use App\Models\Umkm;
use App\Models\User;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class UmkmForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profil UMKM')
                    ->columns(2)
                    ->components([
                        TextInput::make('name')
                            ->label('Nama UMKM')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state)))
                            ->columnSpan(2),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Select::make('region_id')
                            ->label('Wilayah')
                            ->relationship('region', 'kelurahan')
                            ->getOptionLabelFromRecordUsing(fn (Region $record) => $record->full_name)
                            ->searchable()
                            ->preload()
                            ->required(),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpan(2),
                        TextInput::make('wa_number')
                            ->label('Nomor WhatsApp')
                            ->helperText('Contoh: 081234567890')
                            ->required()
                            ->maxLength(20),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->default('pending')
                            ->required(),
                        Toggle::make('is_open')
                            ->label('Sedang Buka')
                            ->default(true),
                        Textarea::make('address')
                            ->label('Alamat')
                            ->required()
                            ->rows(2)
                            ->columnSpan(2),
                        TextInput::make('lat')
                            ->label('Latitude')
                            ->numeric(),
                        TextInput::make('long')
                            ->label('Longitude')
                            ->numeric(),
                        Select::make('user_id')
                            ->label('Akun Pemilik (opsional)')
                            ->relationship('user', 'name')
                            ->options(fn () => User::where('role', 'umkm_owner')->pluck('name', 'id'))
                            ->searchable()
                            ->preload(),
                    ]),
                Section::make('Opsi Pengiriman')
                    ->columns(2)
                    ->components([
                        Toggle::make('pickup_enabled')
                            ->label('Ambil di Toko')
                            ->default(true),
                        Toggle::make('delivery_self_enabled')
                            ->label('Antar Sendiri')
                            ->live(),
                        TextInput::make('delivery_self_fee')
                            ->label('Biaya Antar')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->visible(fn (Get $get) => $get('delivery_self_enabled'))
                            ->columnSpan(2),
                        Toggle::make('delivery_gojek_enabled')
                            ->label('Gojek'),
                        Toggle::make('delivery_grab_enabled')
                            ->label('Grab')
                            ->helperText('Gojek/Grab hanya label pilihan -- pemilik toko yang mengatur pengirimannya sendiri, tidak ada integrasi otomatis.')
                            ->rule(function (Get $get) {
                                return function (string $attribute, $value, Closure $fail) use ($get) {
                                    if (! Umkm::hasAnyDeliveryMethodEnabled($get)) {
                                        $fail('Pilih minimal satu opsi pengiriman.');
                                    }
                                };
                            }),
                    ]),
                Section::make('Media')
                    ->columns(2)
                    ->components([
                        FileUpload::make('logo_path')
                            ->label('Logo/Foto Toko')
                            ->image()
                            ->disk('public')
                            ->directory('umkm-logos'),
                        FileUpload::make('qris_image_path')
                            ->label('Gambar QRIS')
                            ->image()
                            ->disk('public')
                            ->directory('umkm-qris'),
                    ]),
            ]);
    }
}
