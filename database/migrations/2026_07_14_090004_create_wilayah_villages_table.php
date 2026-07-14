<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wilayah_villages', function (Blueprint $table) {
            $table->string('id', 10)->primary();
            $table->string('district_id', 7)->index();
            $table->string('name');

            $table->foreign('district_id')->references('id')->on('wilayah_districts')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wilayah_villages');
    }
};
