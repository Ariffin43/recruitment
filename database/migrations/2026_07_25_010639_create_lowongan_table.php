<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lowongan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fptk_id')
                ->constrained('fptk')
                ->restrictOnDelete();

            $table->string('judul', 150);
            $table->string('lokasi', 150)->nullable();

            $table->enum('tipe_kerja', [
                'fulltime',
                'kontrak',
                'magang',
            ])->nullable();

            $table->enum('status', [
                'draft',
                'dibuka',
                'ditutup',
            ])->default('draft');

            $table->date('tanggal_dibuka')->nullable();
            $table->date('tanggal_ditutup')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lowongan');
    }
};