<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $deptIT = Department::create([
            'code' => 'IT',
            'name' => 'Information Technology'
        ]);

        $deptHR = Department::create([
            'code' => 'HRD',
            'name' => 'Human Resources'
        ]);

        User::create([
            'name' => 'HRD Manager',
            'email' => 'hrd@cesco.co.id',
            'password' => bcrypt('12341234'),
            'role' => 'hrd',
            'status' => 'aktif',
            'department_id' => $deptHR->id
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@cesco.co.id',
            'password' => bcrypt('12341234'),
            'role' => 'hod',
            'status' => 'aktif',
            'department_id' => $deptIT->id
        ]);

        $pelamarData = [
            [
                'name' => 'Andi Saputra',
                'email' => 'andi@gmail.com',
                'phone' => '081234567801',
                'address' => 'Jakarta',
                'status' => 'pending',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@gmail.com',
                'phone' => '081234567802',
                'address' => 'Bandung',
                'status' => 'aktif',
            ],
            [
                'name' => 'Rizky Pratama',
                'email' => 'rizky@gmail.com',
                'phone' => '081234567803',
                'address' => 'Surabaya',
                'status' => 'ditolak',
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@gmail.com',
                'phone' => '081234567804',
                'address' => 'Yogyakarta',
                'status' => 'pending',
            ],
            [
                'name' => 'Fajar Ramadhan',
                'email' => 'fajar@gmail.com',
                'phone' => '081234567805',
                'address' => 'Semarang',
                'status' => 'aktif',
            ],
            [
                'name' => 'Nabila Putri',
                'email' => 'nabila@gmail.com',
                'phone' => '081234567806',
                'address' => 'Medan',
                'status' => 'pending',
            ],
            [
                'name' => 'Yoga Prakoso',
                'email' => 'yoga@gmail.com',
                'phone' => '081234567807',
                'address' => 'Malang',
                'status' => 'ditolak',
            ],
            [
                'name' => 'Citra Maharani',
                'email' => 'citra@gmail.com',
                'phone' => '081234567808',
                'address' => 'Bogor',
                'status' => 'aktif',
            ],
            [
                'name' => 'Arman Hidayat',
                'email' => 'arman@gmail.com',
                'phone' => '081234567809',
                'address' => 'Bekasi',
                'status' => 'pending',
            ],
            [
                'name' => 'Lina Kartika',
                'email' => 'lina@gmail.com',
                'phone' => '081234567810',
                'address' => 'Depok',
                'status' => 'aktif',
            ],
        ];

        foreach ($pelamarData as $pelamar) {
            User::create([
                'name' => $pelamar['name'],
                'email' => $pelamar['email'],
                'phone' => $pelamar['phone'],
                'address' => $pelamar['address'],
                'password' => bcrypt('12341234'),
                'role' => 'pelamar',
                'status' => $pelamar['status'],
                'department_id' => null,
            ]);
        }

        $hodData = [
            'Ahmad Fauzi',
            'Rina Wijaya',
            'Dedi Kurniawan',
            'Sari Puspita',
            'Agus Salim',
            'Maya Lestari',
            'Rudi Hartono',
            'Nina Oktaviani',
            'Bayu Saputra',
            'Intan Permata',
            'Hendra Gunawan',
            'Putri Ayu',
            'Taufik Hidayat',
            'Wulan Sari',
            'Eko Prasetyo',
        ];

        foreach ($hodData as $index => $name) {
            User::create([
                'name' => $name,
                'email' => 'hod' . ($index + 1) . '@cesco.co.id',
                'password' => bcrypt('12341234'),
                'role' => 'hod',
                'status' => 'aktif',
                'department_id' => $index % 2 == 0 ? $deptIT->id : $deptHR->id,
            ]);
        }
    }
}