# SiPKL — Sistem Informasi Praktik Kerja Lapangan

SiPKL adalah platform manajemen program Praktik Kerja Lapangan (PKL) berbasis web yang dirancang khusus untuk mempermudah administrasi antara pihak sekolah, siswa, dan dunia industri. 

Sistem ini membantu mendokumentasikan seluruh rangkaian kegiatan PKL secara transparan dan terstruktur, mulai dari pengajuan tempat, pencatatan jurnal harian, absensi kehadiran, hingga proses penilaian akhir dan pencetakan sertifikat resmi.

---

## 👥 Hak Akses & Fitur Utama

Sistem ini membagi akses menjadi 4 peran utama dengan tanggung jawab masing-masing:

### 1. Siswa
* **Pengajuan PKL Mandiri:** Mengajukan tempat PKL, mengunggah berkas kelengkapan, dan memantau status persetujuan secara *real-time*.
* **Jurnal Harian Digital:** Mencatat aktivitas harian beserta bukti foto dokumentasi langsung di aplikasi.
* **Absensi GPS & Kamera:** Melakukan absen harian secara akurat menggunakan foto selfie (Webcam API) dan koordinat lokasi (Geolocation API).
* **Pengumpulan Laporan Akhir:** Mengunggah draf laporan PKL untuk ditinjau guru pembimbing.
* **Cetak Sertifikat:** Mengunduh dan mencetak sertifikat PKL berformat resmi jika program telah selesai.

### 2. Guru Pembimbing
* **Manajemen Bimbingan:** Memantau seluruh siswa yang berada di bawah bimbingannya.
* **Validasi Kegiatan:** Memeriksa dan memberikan validasi (terima/revisi) pada jurnal harian serta laporan akhir siswa.
* **Input Nilai:** Mengisi evaluasi nilai akhir untuk siswa bimbingan.
* **Notifikasi Sistem:** Mendapat pemberitahuan langsung setiap kali siswa mengunggah berkas baru.

### 3. Pembimbing Industri
* **Manajemen Kuota Magang:** Mengatur batas maksimal kapasitas siswa yang bisa ditampung di perusahaannya.
* **Verifikasi Ganda:** Memvalidasi jurnal kegiatan harian siswa di tempat kerja (sejajar dengan guru).
* **Monitoring Kehadiran:** Memantau rekap absen harian siswa magang.
* **Penilaian Kinerja:** Memberikan nilai langsung berdasarkan performa kerja siswa selama di industri.

### 4. Admin Sekolah
* **Manajemen Master Data:** Melakukan CRUD data siswa, guru, pembimbing industri, dan tempat PKL (perusahaan).
* **Persetujuan Akun & Pengajuan:** Mengaktifkan akun pengguna baru dan memvalidasi pengajuan awal tempat PKL siswa.
* **Penugasan Guru:** Memetakan guru pembimbing untuk siswa yang pengajuannya telah disetujui.

---

## 🛠️ Spesifikasi Teknologi

Sistem ini dibangun menggunakan kombinasi teknologi modern agar performanya ringan, aman, dan mudah dikembangkan:

* **Framework Backend:** Laravel 12 (PHP ^8.2)
* **Starter Kit Autentikasi:** Laravel Breeze
* **Framework Frontend & UI:** Tailwind CSS v3 & Alpine.js (tanpa React/Vue, murni Server-Side Rendering dengan Blade Template)
* **Database:** MySQL
* **Kamera & Lokasi:** HTML5 Webcam API (MediaDevices) & Geolocation API (Native Browser)
* **Notifikasi Asinkron:** Database Channel dengan Queue Worker Laravel
* **Library Eksternal (CDN):** Lucide Icons, Font Awesome 6, dan SweetAlert2

---

## 🚀 Cara Menjalankan Project Secara Lokal

Ikuti langkah-langkah berikut untuk menjalankan aplikasi SiPKL di komputer lokal Anda:

### 1. Persyaratan Awal
Pastikan Anda sudah menginstal:
* PHP versi **8.2** atau lebih baru
* Composer
* Node.js & NPM
* Web Server lokal (seperti XAMPP, Laragon, atau Herd) dengan MySQL aktif.

### 2. Clone Repositori
```bash
git clone https://github.com/MZLforDEX/PKL.git
cd PKL
```

### 3. Setup Project Otomatis
Gunakan script setup bawaan untuk menginstal dependensi PHP/NPM, menyalin file konfigurasi `.env`, men-generate key, serta mem-build aset secara otomatis:
```bash
composer run setup
```

### 4. Konfigurasi Database
Buka file `.env` yang baru dibuat di folder root project, sesuaikan pengaturan databasenya:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Jalankan Migrasi & Seeder Database
```bash
php artisan migrate --seed
```

### 6. Buat Symbolic Link Storage
Agar file dokumen pengajuan, foto jurnal, dan laporan yang diunggah siswa dapat diakses secara publik, jalankan:
```bash
php artisan storage:link
```

### 7. Jalankan Server Dev
Jalankan perintah berikut untuk mengaktifkan local server Laravel, Queue Listener untuk notifikasi, dan Vite Compiler secara bersamaan:
```bash
composer run dev
```
Aplikasi kini dapat diakses melalui browser di alamat **`http://127.0.0.1:8000`**.

---

## 🔑 Akun Uji Coba Default (Seeder)

Untuk keperluan pengujian di lokal, Anda dapat login menggunakan akun-akun demo berikut (semua password adalah `password`):

| Peran (Role) | Email | Deskripsi |
|---|---|---|
| **Admin** | `admin@pkl.test` | Akses penuh master data & approval |
| **Guru Pembimbing** | `guru1@pkl.test` / `guru2@pkl.test` | Bimbingan & input nilai |
| **Siswa** | `siswa1@pkl.test` s/d `siswa5@pkl.test` | Mengisi absen, jurnal, & laporan |
| **Pembimbing Industri** | `pembimbing@pkl.test` | Kontrol industri & kuota magang |

---

## 🧪 Pengujian Sistem
Sistem ini dilengkapi dengan 52+ unit/feature tests otomatis. Anda dapat menjalankan seluruh pengujian dengan mengetik:
```bash
composer run test
```
*(Proses pengujian menggunakan konfigurasi database SQLite *in-memory* sehingga tidak akan mengotori database MySQL utama Anda).*
