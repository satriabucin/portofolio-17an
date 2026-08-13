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
        Schema::create('pendaftar_lomba', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pendaftar')->constrained('pendaftars')->onDelete('cascade');
            $table->foreignId('id_lomba')->constrained('lombas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftar_lomba');
    }
};
