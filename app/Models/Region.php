<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['wilayah_village_id', 'kecamatan', 'kelurahan', 'kota', 'provinsi'])]
class Region extends Model
{
    use HasFactory;

    public function umkms(): HasMany
    {
        return $this->hasMany(Umkm::class);
    }

    public function wilayahVillage(): BelongsTo
    {
        return $this->belongsTo(WilayahVillage::class, 'wilayah_village_id');
    }

    /**
     * @return array<string, string>
     */
    public static function attributesFromVillage(WilayahVillage $village): array
    {
        $village->loadMissing('district.regency.province');

        return [
            'kelurahan' => $village->name,
            'kecamatan' => $village->district->name,
            'kota' => $village->district->regency->name,
            'provinsi' => $village->district->regency->province->name,
        ];
    }

    public static function fromWilayahVillage(WilayahVillage $village): self
    {
        return static::query()->firstOrCreate(
            ['wilayah_village_id' => $village->id],
            static::attributesFromVillage($village),
        );
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => collect([$this->kelurahan, $this->kecamatan, $this->kota, $this->provinsi])
                ->filter()
                ->implode(', '),
        );
    }
}
