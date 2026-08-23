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
        Schema::create('elementary_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
            $table->string('nama');
            $table->string('nisn');
            $table->string('nik');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('tingkat_rombel');
            $table->string('umur');
            $table->string('status');
            $table->string('jenis_kelamin');
            $table->text('alamat');
            $table->string('nomor_telepon');
            $table->string('kebutuhan_khusus');
            $table->string('disabilitas');
            $table->string('nomor_kip_pip');
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->string('nama_wali');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elementary_students');
    }
};
