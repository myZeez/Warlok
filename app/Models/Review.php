<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['umkm_id', 'customer_name', 'rating', 'comment'])]
class Review extends Model
{
    const UPDATED_AT = null;

    public function umkm(): BelongsTo
    {
        return $this->belongsTo(Umkm::class);
    }
}
