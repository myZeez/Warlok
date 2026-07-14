<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->string('provinsi')->nullable()->after('kota');
            $table->string('wilayah_village_id', 10)->nullable()->after('id');

            $table->foreign('wilayah_village_id')->references('id')->on('wilayah_villages')->nullOnDelete();
            $table->unique('wilayah_village_id');
        });
    }

    public function down(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->dropForeign(['wilayah_village_id']);
            $table->dropUnique(['wilayah_village_id']);
            $table->dropColumn(['provinsi', 'wilayah_village_id']);
        });
    }
};
