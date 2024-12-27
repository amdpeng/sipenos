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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('nip_nuptk');
            $table->string('teacher_name');
            $table->enum('gender', ['L', 'P'])->default('L');
            $table->string('religion')->default('Islam');
            $table->date('date_of_birth');
            $table->string('jenis_ptk');
            $table->string('type');
            $table->string('whatsapp_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
