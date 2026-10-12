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
            $table->text('keterangan')->nullable();
            $table->decimal('nominal', 12, 2); // Nominal SPP (Presisi PostgreSQL)
            $table->timestamps();
            // $table->unique([
            //     'student_id', 'tahun_ajaran', 'nama_tagihan',
            // ]);
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
