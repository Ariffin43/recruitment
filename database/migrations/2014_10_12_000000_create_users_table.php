<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('nama', 150);
            $table->string('email', 150)->unique();
            $table->string('password', 255);

            $table->enum('role', [
                'hrd',
                'hod',
                'gm',
                'pelamar',
            ]);

            $table->enum('status', [
                'pending',
                'aktif',
                'nonaktif',
                'ditolak',
            ])->default('pending');

            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};