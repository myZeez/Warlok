<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['kecamatan', 'kelurahan', 'kota'])]
class Region extends Model
{
    use HasFactory;

    public function umkms(): HasMany
    {
        return $this->hasMany(Umkm::class);
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->kelurahan}, {$this->kecamatan}, {$this->kota}",
        );
    }
}
