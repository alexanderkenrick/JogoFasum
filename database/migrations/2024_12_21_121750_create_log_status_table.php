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
        Schema::create('log_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->references('id')->on('laporans');
            $table->foreignId('updated_by')->references('id')->on('users');
            $table->enum('status_sebelum', ['Antri', 'Dikerjakan', 'Outsource', 'Selesai', 'Tidak terselesaikan'])->nullable();
            $table->enum('status_sesudah', ['Antri', 'Dikerjakan', 'Outsource', 'Selesai', 'Tidak terselesaikan'])->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_status');
    }
};
