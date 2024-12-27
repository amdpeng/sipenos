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
        Schema::create('nomor_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->string('type_surat');
            $table->year('tahun')->nullable();
            $table->date('tanggal_surat');
            $table->string('keterangan_surat');
            $table->string('nama_guru')->nullable();
            $table->enum('status', ['Pending', 'Validated'])->default('Pending');
	        $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nomor_keluars');
    }
};
