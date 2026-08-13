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
        Schema::create('jadwal_acaras', function (Blueprint $table) {
            $table->id();
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai')->nullable();
            $table->string('kegiatan');
            $table->text('deskripsi')->nullable();
            $table->string('lokasi')->nullable();
            $table->unsignedBigInteger('id_lomba')->nullable();
            $table->timestamps();

            $table->foreign('id_lomba')->references('id')->on('lombas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_acaras');
    }
};
