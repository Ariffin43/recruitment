<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lamaran_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lamaran_id')
                ->constrained('lamaran')
                ->cascadeOnDelete();

            $table->string('status_lama', 50)->nullable();
            $table->string('status_baru', 50);

            $table->string('aksi', 50)->nullable();
            $table->text('catatan')->nullable();

            $table->foreignId('changed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('created_at')
                ->useCurrent();

            $table->index('lamaran_id');
            $table->index('changed_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lamaran_histories');
    }
};