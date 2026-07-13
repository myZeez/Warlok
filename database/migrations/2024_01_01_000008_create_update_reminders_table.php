<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('update_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_id')->constrained()->cascadeOnDelete();
            $table->timestamp('sent_at')->useCurrent();
            $table->enum('status', ['sent', 'failed']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('update_reminders');
    }
};
