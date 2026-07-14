<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wilayah_regencies', function (Blueprint $table) {
            $table->string('id', 4)->primary();
            $table->string('province_id', 2)->index();
            $table->string('name');

            $table->foreign('province_id')->references('id')->on('wilayah_provinces')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wilayah_regencies');
    }
};
