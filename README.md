# E-Recruitment System

## Deskripsi Project

E-Recruitment System merupakan aplikasi berbasis web yang dikembangkan untuk mendigitalisasi dan mengintegrasikan proses rekrutmen karyawan dalam satu sistem.

Sistem ini mendigitalisasi proses **Formulir Permintaan Tenaga Kerja (FPTK)** yang sebelumnya dapat dilakukan secara manual menjadi proses digital dengan alur pengajuan dan persetujuan yang lebih terstruktur. HOD dapat mengajukan kebutuhan tenaga kerja melalui FPTK, kemudian proses persetujuan dilakukan secara berjenjang oleh GM dan HRD sebelum lowongan pekerjaan dibuka.

Selain mendigitalisasi proses FPTK, sistem ini juga menyediakan proses rekrutmen yang terintegrasi mulai dari pendaftaran akun pelamar, pengelolaan profil, pengajuan lamaran, proses screening dan approval oleh HRD dan HOD, hingga penjadwalan interview.

Setiap proses memiliki status yang dapat dipantau oleh pengguna sesuai dengan role masing-masing. Hal ini memberikan transparansi terhadap perkembangan FPTK, lowongan pekerjaan dan lamaran yang sedang diproses.

Sistem ini juga membantu mengurangi proses manual, meningkatkan efisiensi proses rekrutmen, meminimalkan risiko kehilangan informasi, serta memudahkan setiap pihak dalam memantau proses rekrutmen secara terstruktur.

---

## Fitur Sistem

### FPTK
* Pengajuan FPTK oleh HOD
* Approval FPTK oleh GM
* Approval FPTK oleh HRD
* Pemantauan status FPTK
* Pembuatan lowongan berdasarkan FPTK yang telah disetujui

### Manajemen Lowongan
* Pembuatan lowongan oleh HRD
* Pembukaan lowongan berdasarkan FPTK yang telah disetujui
* Pengaturan informasi lowongan
* Pengelolaan status lowongan

### Manajemen Akun Pelamar
* Pendaftaran akun pelamar
* Approval akun pelamar oleh HRD
* Aktivasi akun setelah disetujui
* Pelamar hanya dapat login setelah akun disetujui

### Profil Pelamar
* Pengisian data profil pelamar
* Pengisian informasi pribadi dan data pendukung
* Pengunggahan dokumen persyaratan
* Persentase kelengkapan profil
* Pelamar wajib melengkapi profil hingga 100% sebelum mengajukan lamaran

### Lamaran Pekerjaan
* Pengajuan lamaran ke lowongan yang sedang dibuka
* Screening lamaran oleh HRD dan HOD
* Approval lamaran oleh HRD dan HOD
* Pemantauan status lamaran

### Interview
* Pembuatan jadwal interview oleh HRD
* Penentuan metode interview
* Penentuan waktu dan lokasi atau link interview
* Informasi jadwal interview pada halaman lamaran pelamar
* Pengiriman informasi jadwal interview melalui email pelamar

### Pemantauan Status
Pengguna dapat memantau status proses sesuai dengan role dan aksesnya, seperti:
* Status FPTK
* Status lowongan
* Status akun pelamar
* Status lamaran
* Status proses approval
* Status jadwal interview

---

## Role Pengguna

### HRD
HRD memiliki peran utama dalam pengelolaan proses rekrutmen.

HRD dapat:
* Mengelola data master (Departemen, Akun HOD dan Kualifikasi tiap departemen)
* Melakukan approval akun pelamar
* Melakukan approval lamaran
* Mengirim lamaran kepada HOD untuk proses approval
* Melakukan approval akhir setelah lamaran disetujui HOD
* Membuat dan membuka lowongan berdasarkan FPTK yang telah disetujui
* Membuat jadwal interview
* Mengirimkan informasi jadwal interview kepada pelamar
* Memantau proses rekrutmen

---

### HOD
HOD berperan dalam mengajukan kebutuhan tenaga kerja dan melakukan proses approval kandidat.

HOD dapat:
* Mengajukan FPTK
* Memantau status FPTK
* Melakukan revisi terhadap FPTK apabila ada revisi
* Melakukan approval terhadap kandidat yang telah lolos proses screening HRD

---

### GM
GM berperan dalam melakukan persetujuan terhadap kebutuhan tenaga kerja yang diajukan oleh HOD.

GM dapat:
* Melihat FPTK yang diajukan oleh HOD
* Menyetujui FPTK
* Menolak FPTK
* Mengembalikan FPTK untuk revisi apabila diperlukan
* Memantau status pengajuan FPTK

---

### Pelamar
Pelamar dapat:

* Membuat akun
* Melengkapi profil
* Mengunggah dokumen persyaratan
* Melihat lowongan yang sedang dibuka
* Mengajukan lamaran
* Melihat status lamaran
* Melihat informasi jadwal interview
* Menerima informasi jadwal interview melalui email

---

## Alur E-Recruitment

### 1. HOD Mengajukan FPTK
Proses rekrutmen dimulai ketika HOD mengajukan **Formulir Permintaan Tenaga Kerja (FPTK)**.
FPTK berisi informasi mengenai kebutuhan tenaga kerja, seperti:
* Departemen
* Posisi yang dibutuhkan
* Jumlah kebutuhan
* Tanggal kebutuhan
* Alasan kebutuhan tenaga kerja
* Kualifikasi yang dibutuhkan

Setelah FPTK diajukan, status FPTK menjadi Pending GM

---

### 2. GM Melakukan Approval FPTK
GM melakukan review terhadap FPTK yang diajukan oleh HOD.
GM dapat:
* Menyetujui FPTK
* Menolak FPTK
* Meminta revisi FPTK

Jika FPTK disetujui oleh GM, maka proses dilanjutkan ke HRD.

---

### 3. HRD Melakukan Approval FPTK
Setelah FPTK disetujui oleh GM, HRD melakukan proses approval berikutnya.
Jika HRD menyetujui FPTK, maka FPTK dianggap telah disetujui dan dapat digunakan sebagai dasar untuk membuka lowongan pekerjaan.

---

### 4. HRD Membuka Lowongan
Setelah FPTK mendapatkan persetujuan, HRD dapat membuat dan membuka lowongan berdasarkan FPTK tersebut.
Lowongan berisi informasi seperti:
* Judul posisi
* Departemen
* Posisi yang dibutuhkan
* Kualifikasi
* Lokasi
* Tipe pekerjaan
* Periode lowongan

Lowongan kemudian dapat dibuka agar dapat dilihat oleh pelamar.

---

### 5. Pelamar Mendaftarkan Akun
Pelamar yang belum memiliki akun dapat melakukan pendaftaran.
Setelah melakukan pendaftaran, akun pelamar belum dapat langsung digunakan untuk login.
Akun akan memiliki status menunggu persetujuan HRD.

---

### 6. Pelamar Melengkapi Profil
Sebelum dapat mengajukan lamaran, pelamar harus melengkapi profil hingga mencapai tingkat kelengkapan **100%**.
Data yang perlu dilengkapi dapat mencakup:
* Informasi pribadi
* Informasi kontak
* Alamat
* Pendidikan
* Pengalaman kerja
* Dokumen pendukung

Pelamar tidak dapat mengajukan lamaran apabila profil belum lengkap.

---

### 7. Pelamar Mengajukan Lamaran
Setelah profil mencapai 100%, pelamar dapat memilih salah satu lowongan yang sedang dibuka dan mengajukan lamaran.

Lamaran kemudian masuk ke dalam proses screening HRD.

---

### 8. HRD Melakukan Screening dan Approval
HRD melakukan review terhadap lamaran yang dikirimkan oleh pelamar.
HRD dapat:
* Menyetujui lamaran
* Menolak lamaran
* Memberikan catatan terhadap lamaran

Jika lamaran disetujui oleh HRD, maka lamaran diteruskan kepada HOD untuk proses berikutnya.

---

### 9. HOD Melakukan Approval Lamaran
HOD melakukan review terhadap kandidat yang telah disetujui oleh HRD.
HOD dapat:
* Menyetujui kandidat
* Menolak kandidat
* Memberikan catatan

Jika HOD menyetujui lamaran, maka lamaran dikembalikan kepada HRD untuk proses penjadwalan interview.

---

### 10. HRD Membuat Jadwal Interview
Setelah lamaran disetujui oleh HOD, HRD dapat membuat jadwal interview.
Informasi interview dapat meliputi:
* Tanggal interview
* Waktu interview
* Metode interview
* Lokasi interview untuk interview offline
* Link interview untuk interview online

Setelah jadwal interview dibuat, informasi interview akan ditampilkan pada halaman lamaran pelamar.
Informasi jadwal interview juga akan dikirimkan melalui email pelamar.

---

## Informasi Jadwal Interview pada Halaman Pelamar
Pada halaman **Lamaran**, pelamar dapat melihat daftar lamaran yang pernah diajukan.
Setiap data lamaran memiliki informasi terkait proses lamaran.
Pelamar juga dapat menekan icon informasi untuk melihat detail lebih lengkap mengenai lamaran.
Informasi jadwal interview akan tersedia setelah HRD membuat jadwal interview.
Detail tersebut dapat berisi:
* Status lamaran
* Posisi yang dilamar
* Tanggal interview
* Waktu interview
* Metode interview
* Lokasi interview atau link interview
* Informasi tambahan lainnya

---

## Alur E-Recruitment Secara Keseluruhan

```text
┌─────────────────────┐
│ HOD Mengajukan FPTK │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ GM Approval FPTK    │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ HRD Approval FPTK   │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ HRD Membuka         │
│ Lowongan            │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Pelamar Mendaftar   │
│ Akun                │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ HRD Approval Akun   │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Pelamar Login       │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Melengkapi Profil   │
│ Hingga 100%         │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Pelamar Mengajukan  │
│ Lamaran             │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ HRD Approval        │
│ Lamaran             │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ HOD Approval        │
│ Lamaran             │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ HRD Membuat Jadwal  │
│ Interview           │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Email & Informasi   │
│ Interview Pelamar   │
└─────────────────────┘
```

---

---
## Instalasi Project

### 1. Clone Repository
git clone <repository-url>

Masuk ke direktori project:
cd e-recruitment

---

### 2. Install Dependency Laravel
Install dependency PHP menggunakan Composer:
composer install

---

### 3. Install Dependency Frontend
Install dependency frontend menggunakan NPM:
npm install

---

### 4. Konfigurasi Environment
Salin file `.env.example` menjadi `.env`.

Linux atau macOS:
cp .env.example .env

Windows:
copy .env.example .env

---

### 5. Konfigurasi Database
Buat database dengan nama:
recruitment

Kemudian sesuaikan konfigurasi database pada file `.env`:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=recruitment
DB_USERNAME=root
DB_PASSWORD=

Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` dengan konfigurasi database lokal.

---

### 6. Generate Application Key
php artisan key:generate

---

### 7. Menjalankan Migration dan Seeder
Untuk membuat struktur database dan mengisi data awal:
php artisan migrate:fresh --seed

Perintah tersebut akan:
* Menghapus tabel yang sudah ada
* Menjalankan seluruh migration
* Mengisi data awal melalui `DatabaseSeeder`

---

### 8. Membuat Symbolic Link Storage
Untuk mengakses file atau dokumen yang disimpan pada storage Laravel:
php artisan storage:link

---

## Seeder dan Akun Login
Seeder menyediakan akun demo untuk setiap role dalam sistem.
Semua akun menggunakan password:
12341234

| Role    | Email               | Password   |
| ------- | ------------------- | ---------- |
| HRD     | `hrd@gmail.com`     | `12341234` |
| HOD     | `hod@gmail.com`     | `12341234` |
| GM      | `gm@gmail.com`      | `12341234` |
| Pelamar | `pelamar@gmail.com` | `12341234` |

---

## Cara Menjalankan Project

Project menggunakan Laravel sebagai backend dan Vite sebagai frontend asset bundler.

* Jalankan server Laravel:
php artisan serve

* Kemudian jalankan Vite pada terminal lain:
npm run dev

Setelah kedua proses berjalan, project dapat diakses melalui alamat yang ditampilkan oleh Laravel.
http://127.0.0.1:8000

---

## Perintah Development

### Menjalankan Laravel
php artisan serve

### Menjalankan Vite
npm run dev

### Menjalankan Migration
php artisan migrate

### Menjalankan Seeder
php artisan db:seed

### Reset Database dan Seeder
php artisan migrate:fresh --seed

### Membuat Storage Link
php artisan storage:link

---
