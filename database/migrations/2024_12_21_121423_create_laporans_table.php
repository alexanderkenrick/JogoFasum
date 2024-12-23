<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['Antri', 'Dikerjakan', 'Selesai', 'Tidak terselesaikan'])->default('Antri');
            $table->foreignId('created_by')->references('id')->on('users');
            $table->timestamp('created_at')->useCurrent();
            $table->foreignId('updated_by')->references('id')->on('users');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->text('deskripsi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
