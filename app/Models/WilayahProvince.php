<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table(keyType: 'string', incrementing: false, timestamps: false)]
#[Fillable(['id', 'name'])]
class WilayahProvince extends Model
{
    public function regencies(): HasMany
    {
        return $this->hasMany(WilayahRegency::class, 'province_id');
    }
}
