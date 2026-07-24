<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelamar', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('jenis_kelamin', [
                'L',
                'P',
            ])->nullable();

            $table->string('no_hp', 20);
            $table->text('alamat')->nullable();
            $table->string('pendidikan_terakhir', 100)->nullable();
            $table->string('pengalaman_kerja', 255)->nullable();

            $table->string('foto', 255)->nullable();
            $table->string('file_ktp', 255)->nullable();
            $table->string('file_kk', 255)->nullable();
            $table->string('file_cv', 255)->nullable();
            $table->string('file_ijazah', 255)->nullable();
            $table->string('file_sertifikat', 255)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelamar');
    }
};