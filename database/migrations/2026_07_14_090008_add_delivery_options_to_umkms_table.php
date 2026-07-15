<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('umkms', function (Blueprint $table) {
            $table->boolean('pickup_enabled')->default(true)->after('is_open');
            $table->boolean('delivery_self_enabled')->default(false)->after('pickup_enabled');
            $table->decimal('delivery_self_fee', 10, 2)->default(0)->after('delivery_self_enabled');
            $table->boolean('delivery_gojek_enabled')->default(false)->after('delivery_self_fee');
            $table->boolean('delivery_grab_enabled')->default(false)->after('delivery_gojek_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('umkms', function (Blueprint $table) {
            $table->dropColumn([
                'pickup_enabled',
                'delivery_self_enabled',
                'delivery_self_fee',
                'delivery_gojek_enabled',
                'delivery_grab_enabled',
            ]);
        });
    }
};
