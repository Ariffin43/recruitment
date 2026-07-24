<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lamaran', function (Blueprint $table) {
            $table->id();

            $table->string('nomor_lamaran', 50)->unique();

            $table->foreignId('lowongan_id')
                ->constrained('lowongan')
                ->cascadeOnDelete();

            $table->foreignId('pelamar_id')
                ->constrained('pelamar')
                ->cascadeOnDelete();

            $table->enum('status', [
                'baru',
                'screening_hrd',
                'ditolak_hrd',
                'dikirim_ke_hod',
                'screening_hod',
                'ditolak_hod',
                'menunggu_interview',
                'interview',
                'selesai',
            ])->default('baru');

            $table->enum('hasil_akhir', [
                'diterima',
                'ditolak',
                'cadangan',
            ])->nullable();

            $table->text('catatan_hrd')->nullable();
            $table->text('catatan_hod')->nullable();
            $table->text('catatan_interview')->nullable();

            $table->dateTime('tanggal_dilamar')
                ->useCurrent();

            $table->dateTime('tanggal_screening_hrd')->nullable();
            $table->dateTime('tanggal_dikirim_ke_hod')->nullable();
            $table->dateTime('tanggal_screening_hod')->nullable();
            $table->dateTime('tanggal_interview')->nullable();

            $table->enum('metode_interview', [
                'offline',
                'online',
            ]);

            $table->string('lokasi_interview', 255)->nullable();
            $table->string('link', 255)->nullable();

            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lamaran');
    }
};