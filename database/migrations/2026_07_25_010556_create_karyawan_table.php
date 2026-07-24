<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('departemen_id')
                ->constrained('departemen')
                ->restrictOnDelete();

            $table->string('badge_id', 50)->unique();
            $table->string('jabatan', 100);
            $table->string('no_hp', 20)->nullable();

            $table->enum('jenis_kelamin', [
                'L',
                'P',
            ])->nullable();

            $table->text('alamat')->nullable();
            $table->string('foto', 255)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};