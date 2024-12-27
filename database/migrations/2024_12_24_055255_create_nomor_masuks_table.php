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
        Schema::create('nomor_masuks', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->date('tanggal_surat');
            $table->string('pengirim');
            $table->text('keterangan_surat')->nullable();
            $table->enum('satuan_pendidikan', ['SD', 'SMP', 'SMA','SMK','Perguruan Tinggi','Perangkat Desa']);
            $table->enum('status', ['Diterima', 'Belum Diterima'])->default('Belum Diterima');
            $table->string('file_surat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nomor_masuks');
    }
};
