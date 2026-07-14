<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table(keyType: 'string', incrementing: false, timestamps: false)]
#[Fillable(['id', 'district_id', 'name'])]
class WilayahVillage extends Model
{
    public function district(): BelongsTo
    {
        return $this->belongsTo(WilayahDistrict::class, 'district_id');
    }
}
