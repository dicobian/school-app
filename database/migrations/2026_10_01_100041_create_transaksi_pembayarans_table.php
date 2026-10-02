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
        Schema::create('transaksi_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('elementary_students')->cascadeOnDelete();
            $table->string('kategori_pembayaran');
            $table->string('nama_pembayaran');
            $table->date('tanggal_bayar');
            $table->decimal('nominal', 12, 2); // Nominal SPP (Presisi PostgreSQL)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_pembayarans');
    }
};
