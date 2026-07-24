<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('users')->insert([
            [
                'nama' => 'Sarah Wijaya',
                'email' => 'hrd@gmail.com',
                'password' => Hash::make('12341234'),
                'role' => 'hrd',
                'status' => 'aktif',
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Michael Tan',
                'email' => 'hod@gmail.com',
                'password' => Hash::make('12341234'),
                'role' => 'hod',
                'status' => 'aktif',
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'David Anderson',
                'email' => 'gm@gmail.com',
                'password' => Hash::make('12341234'),
                'role' => 'gm',
                'status' => 'aktif',
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Rafael Pratama',
                'email' => 'pelamar@gmail.com',
                'password' => Hash::make('12341234'),
                'role' => 'pelamar',
                'status' => 'aktif',
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('departemen')->insert([
            [
                'kode' => 'IT',
                'nama' => 'Information Technology',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode' => 'HRD',
                'nama' => 'Human Resource Development',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode' => 'FIN',
                'nama' => 'Finance and Accounting',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode' => 'GM',
                'nama' => 'General Manager',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('kualifikasi')->insert([
            [
                'departemen_id' => 1,
                'nama_kualifikasi' => '
                    Menguasai Laravel
                    PHP
                    MySQL
                ',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'departemen_id' => 2,
                'nama_kualifikasi' => '
                    Pengalaman 1 tahun di bidang ini
                    Ahli dalam menggunakan MS apapun
                ',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'departemen_id' => 3,
                'nama_kualifikasi' => '
                    Memahami proses akuntansi
                    pengelolaan laporan keuangan
                 ',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('karyawan')->insert([
            [
                'user_id' => 1,
                'departemen_id' => 2,
                'badge_id' => 'HRD001',
                'jabatan' => 'HRD Manager',
                'no_hp' => '081211111111',
                'jenis_kelamin' => 'P',
                'alamat' => 'Batam, Kepulauan Riau',
                'foto' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => 2,
                'departemen_id' => 1,
                'badge_id' => 'HOD001',
                'jabatan' => 'Head of Information Technology',
                'no_hp' => '081222222222',
                'jenis_kelamin' => 'L',
                'alamat' => 'Batam, Kepulauan Riau',
                'foto' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => 3,
                'departemen_id' => 4,
                'badge_id' => 'GM001',
                'jabatan' => 'General Manager',
                'no_hp' => '081233333333',
                'jenis_kelamin' => 'L',
                'alamat' => 'Batam, Kepulauan Riau',
                'foto' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('fptk')->insert([
            [
                'nomor_fptk' => 'FPTK-2026-0001',
                'departemen_id' => 1,
                'hod_id' => 2,
                'posisi_dibutuhkan' => 'Software Developer',
                'jumlah_kebutuhan' => 2,
                'tanggal_dibutuhkan' => '2026-08-01',
                'alasan' => 'Penambahan kebutuhan tenaga kerja untuk mendukung pengembangan dan pemeliharaan sistem perusahaan.',
                'catatan_tambahan' => 'Kandidat diharapkan memiliki pengalaman dalam pengembangan aplikasi berbasis web.',
                'status' => 'pending_gm',
                'catatan_gm' => null,
                'gm_approved_at' => null,
                'catatan_hrd' => null,
                'hrd_approved_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}