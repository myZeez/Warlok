<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'umkm_id', 'user_id', 'buyer_name', 'buyer_phone', 'buyer_address',
    'delivery_method', 'delivery_fee', 'subtotal', 'total', 'status', 'notes',
])]
class Order extends Model
{
    protected function casts(): array
    {
        return [
            'delivery_fee' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function umkm(): BelongsTo
    {
        return $this->belongsTo(Umkm::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function deliveryMethodLabel(): string
    {
        return match ($this->delivery_method) {
            'pickup' => 'Ambil di Toko',
            'self_delivery' => 'Antar Sendiri',
            'gojek' => 'Gojek',
            'grab' => 'Grab',
        };
    }

    public function waSummaryMessage(): string
    {
        $lines = ["Halo, saya mau pesan dari Warlok:"];

        foreach ($this->items as $item) {
            $lines[] = "- {$item->quantity}x {$item->product_name} (Rp".number_format($item->price, 0, ',', '.').')';
        }

        $lines[] = '';
        $lines[] = 'Subtotal: Rp'.number_format($this->subtotal, 0, ',', '.');
        $lines[] = 'Pengiriman ('.$this->deliveryMethodLabel().'): Rp'.number_format($this->delivery_fee, 0, ',', '.');
        $lines[] = 'Total: Rp'.number_format($this->total, 0, ',', '.');
        $lines[] = '';
        $lines[] = "Nama: {$this->buyer_name}";

        if ($this->buyer_address) {
            $lines[] = "Alamat: {$this->buyer_address}";
        }

        if ($this->notes) {
            $lines[] = "Catatan: {$this->notes}";
        }

        return implode("\n", $lines);
    }
}
