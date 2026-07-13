<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable([
    'user_id', 'region_id', 'name', 'slug', 'description', 'wa_number',
    'address', 'lat', 'long', 'logo_path', 'qris_image_path', 'status', 'is_open',
])]
class Umkm extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'lat' => 'decimal:7',
            'long' => 'decimal:7',
            'is_open' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Umkm $umkm) {
            $umkm->slug ??= static::uniqueSlug($umkm->name);
        });
    }

    public static function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$base}-".++$i;
        }

        return $slug;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function updateReminders(): HasMany
    {
        return $this->hasMany(UpdateReminder::class);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('status', 'active');
    }

    /**
     * Great-circle distance from a given point, in kilometers.
     * Computed in PHP (not SQL) so it works the same on SQLite (dev) and MySQL (prod).
     */
    public function distanceInKmFrom(?float $lat, ?float $long): ?float
    {
        if ($lat === null || $long === null || $this->lat === null || $this->long === null) {
            return null;
        }

        $earthRadiusKm = 6371;

        $latDelta = deg2rad((float) $this->lat - $lat);
        $longDelta = deg2rad((float) $this->long - $long);

        $a = sin($latDelta / 2) ** 2
            + cos(deg2rad($lat)) * cos(deg2rad((float) $this->lat)) * sin($longDelta / 2) ** 2;

        return $earthRadiusKm * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    public function waLink(?string $message = null): string
    {
        $number = preg_replace('/\D/', '', $this->wa_number);

        if (str_starts_with($number, '0')) {
            $number = '62'.substr($number, 1);
        }

        $text = $message ?? "Halo {$this->name}, saya lihat toko kamu di Warlok.";

        return 'https://wa.me/'.$number.'?text='.rawurlencode($text);
    }
}
