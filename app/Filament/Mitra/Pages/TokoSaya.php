<?php

namespace App\Filament\Mitra\Pages;

use App\Models\Umkm;
use BackedEnum;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class TokoSaya extends Page
{
    protected static string $routePath = '/';

    public static function getRoutePath(Panel $panel): string
    {
        return static::$routePath;
    }

    protected static ?string $navigationLabel = 'Toko Saya';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?string $title = 'Toko Saya';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getRecord()->attributesToArray());
    }

    protected function getRecord(): Umkm
    {
        return auth()->user()->umkm ?? abort(404);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->model($this->getRecord())
            ->statePath('data')
            ->operation('edit');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Text::make(fn () => 'Status Toko: '.match ($this->getRecord()->status) {
                'active' => 'Aktif — tampil di katalog Warlok',
                'inactive' => 'Nonaktif — tidak tampil di katalog',
                default => 'Menunggu Persetujuan — tim kami sedang meninjau tokomu',
            })->badge(),
            TextInput::make('name')
                ->label('Nama UMKM')
                ->required()
                ->maxLength(255),
            Textarea::make('description')
                ->label('Deskripsi')
                ->rows(3),
            TextInput::make('wa_number')
                ->label('Nomor WhatsApp')
                ->required()
                ->maxLength(20),
            Textarea::make('address')
                ->label('Alamat')
                ->required()
                ->rows(2),
            TextInput::make('lat')
                ->numeric()
                ->hidden()
                ->dehydratedWhenHidden(),
            TextInput::make('long')
                ->numeric()
                ->hidden()
                ->dehydratedWhenHidden(),
            ViewField::make('location_picker')
                ->label('Titik Lokasi (opsional)')
                ->view('filament.mitra.location-picker')
                ->dehydrated(false),
            Toggle::make('is_open')
                ->label('Sedang Buka'),
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
                        ->helperText('Gojek/Grab hanya label pilihan -- kamu yang mengatur pengirimannya sendiri, tidak ada integrasi otomatis.')
                        ->rule(function (Get $get) {
                            return function (string $attribute, $value, Closure $fail) use ($get) {
                                if (! Umkm::hasAnyDeliveryMethodEnabled($get)) {
                                    $fail('Pilih minimal satu opsi pengiriman.');
                                }
                            };
                        }),
                ]),
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
        ]);
    }

    public function save(): void
    {
        $this->getRecord()->update($this->form->getState());

        Notification::make()
            ->title('Profil toko diperbarui')
            ->success()
            ->send();
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            $this->getFormContentComponent(),
        ]);
    }

    protected function getFormContentComponent(): Component
    {
        return Form::make([EmbeddedSchema::make('form')])
            ->id('form')
            ->livewireSubmitHandler('save')
            ->footer([
                Actions::make([
                    Action::make('save')
                        ->label('Simpan')
                        ->submit('save')
                        ->keyBindings(['mod+s']),
                ]),
            ]);
    }
}
