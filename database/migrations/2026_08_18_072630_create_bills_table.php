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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('elementary_students')->cascadeOnDelete();
            $table->string('nama_tagihan');
            $table->string('tahun_ajaran');
            $table->enum('bulan', ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'])->nullable();
            $table->decimal('nominal', 12, 2); // Nominal SPP (Presisi PostgreSQL)
            $table->enum('status', ['belum_lunas', 'lunas'])->default('belum_lunas');
            $table->date('tanggal_bayar')->nullable();
            $table->timestamps();
            $table->unique([
                'student_id', 'tahun_ajaran', 'nama_tagihan', 'bulan'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
