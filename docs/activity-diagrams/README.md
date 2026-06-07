# Activity Diagram — SiPKL

Diagram aktivitas UML (PlantUML) untuk setiap fitur dalam Sistem Informasi Praktik Kerja Lapangan (SiPKL).

## Cara Menggunakan

Buka file `.puml` di [PlantUML Editor](https://www.plantuml.com/plantuml/uml/) atau gunakan ekstensi VS Code (e.g. `jebbs.plantuml`) untuk merender diagram.

## Daftar Diagram

| File | Fitur | Aktor |
|------|-------|-------|
| `auth.puml` | Login & Registrasi | Pengguna umum, Sistem |
| `pengajuan.puml` | Pengajuan PKL (full workflow) | Siswa, Admin, Guru, Sistem |
| `jurnal.puml` | Jurnal Harian PKL | Siswa, Guru, Pembimbing Industri, Sistem |
| `laporan.puml` | Laporan Akhir PKL | Siswa, Guru, Sistem |
| `penilaian.puml` | Penilaian PKL | Guru, Sistem |
| `absensi.puml` | Absensi Harian PKL | Siswa, Guru, Pembimbing Industri, Sistem |
| `sertifikat.puml` | Cetak Sertifikat PKL | Siswa, Sistem |
| `messaging.puml` | Hubungi Sekolah & Hubungi Admin | Pembimbing, Guru, Admin, Sistem |
| `admin.puml` | Admin Panel (Master Data & Approve User) | Admin, Sistem |
| `profile.puml` | Manajemen Profil & Keamanan | User (semua role), Sistem |
