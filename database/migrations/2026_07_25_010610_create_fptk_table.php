<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fptk', function (Blueprint $table) {
            $table->id();

            $table->string('nomor_fptk', 50)->unique();

            $table->foreignId('departemen_id')
                ->constrained('departemen')
                ->cascadeOnDelete();

            $table->foreignId('hod_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('posisi_dibutuhkan', 150);
            $table->integer('jumlah_kebutuhan');
            $table->date('tanggal_dibutuhkan');
            $table->text('alasan');
            $table->text('catatan_tambahan')->nullable();

            $table->enum('status', [
                'pending_gm',
                'revisi_gm',
                'approved_gm',
                'revisi_hrd',
                'approved_hrd',
                'ditolak',
            ])->default('pending_gm');

            $table->text('catatan_gm')->nullable();
            $table->timestamp('gm_approved_at')->nullable();

            $table->text('catatan_hrd')->nullable();
            $table->timestamp('hrd_approved_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fptk');
    }
};