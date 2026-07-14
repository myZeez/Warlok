<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wilayah_districts', function (Blueprint $table) {
            $table->string('id', 7)->primary();
            $table->string('regency_id', 4)->index();
            $table->string('name');

            $table->foreign('regency_id')->references('id')->on('wilayah_regencies')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wilayah_districts');
    }
};
