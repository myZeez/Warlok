<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wilayah_provinces', function (Blueprint $table) {
            $table->string('id', 2)->primary();
            $table->string('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wilayah_provinces');
    }
};
