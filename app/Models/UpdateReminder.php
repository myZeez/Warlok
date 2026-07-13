<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['umkm_id', 'sent_at', 'status'])]
class UpdateReminder extends Model
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    public function umkm(): BelongsTo
    {
        return $this->belongsTo(Umkm::class);
    }
}
