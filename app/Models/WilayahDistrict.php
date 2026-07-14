<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table(keyType: 'string', incrementing: false, timestamps: false)]
#[Fillable(['id', 'regency_id', 'name'])]
class WilayahDistrict extends Model
{
    public function regency(): BelongsTo
    {
        return $this->belongsTo(WilayahRegency::class, 'regency_id');
    }

    public function villages(): HasMany
    {
        return $this->hasMany(WilayahVillage::class, 'district_id');
    }
}
