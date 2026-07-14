<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table(keyType: 'string', incrementing: false, timestamps: false)]
#[Fillable(['id', 'province_id', 'name'])]
class WilayahRegency extends Model
{
    public function province(): BelongsTo
    {
        return $this->belongsTo(WilayahProvince::class, 'province_id');
    }

    public function districts(): HasMany
    {
        return $this->hasMany(WilayahDistrict::class, 'regency_id');
    }
}
