# LAPORAN ANALISIS INPUT, PROSES, DAN OUTPUT (IPO)
## SISTEM INFORMASI PRAKTIK KERJA LAPANGAN (SiPKL / SPARTA)

Laporan ini disusun untuk memberikan panduan komprehensif mengenai aliran data pada aplikasi **SiPKL (Sistem Informasi Praktik Kerja Lapangan)**. Informasi ini dirancang khusus untuk mempermudah perancangan **Diagram Konteks (Context Diagram)** dan **Data Flow Diagram (DFD) Level 0**.

---

## 1. ENTITAS LUAR (EXTERNAL ENTITIES)

Sistem SiPKL berinteraksi dengan **4 entitas luar** utama:

1. **Siswa**: Peserta PKL yang melakukan pengajuan tempat, mencatat absensi, mengisi jurnal harian, mengunggah laporan, dan mengunduh sertifikat.
2. **Guru Pembimbing**: Guru sekolah yang mengelola bimbingan, memverifikasi pengajuan PKL, memvalidasi jurnal, mereview laporan, dan menginput penilaian akhir.
3. **Pembimbing Industri**: Perwakilan dari tempat industri/perusahaan yang memantau absensi siswa magang, memvalidasi jurnal harian, dan mengirim pesan koordinasi ke sekolah.
4. **Administrator (Admin)**: Pengelola sistem yang mengelola data master, menyetujui registrasi user baru, memetakan (plotting) guru pembimbing ke siswa, dan merespon pesan koordinasi.

---

## 2. DIAGRAM KONTEKS: ALIRAN INPUT & OUTPUT GLOBAL

Diagram Konteks menggambarkan batasan sistem dan aliran data masuk (Input) serta data keluar (Output) dari/ke entitas luar tanpa memperlihatkan proses internal secara detail.

### A. Tabel Pemetaan Aliran Data Diagram Konteks

| Entitas Luar | Aliran Data Masuk (INPUT ke Sistem) | Aliran Data Keluar (OUTPUT dari Sistem) |
| :--- | :--- | :--- |
| **Siswa** | 1. Data Registrasi & Profil (NIS, kelas, jurusan)<br>2. Form Pengajuan PKL & Berkas Pendukung<br>3. Absensi Harian / Clock-In (Foto selfie & GPS)<br>4. Jurnal Harian & Bukti Dokumentasi Kegiatan<br>5. Berkas Laporan Akhir (PDF) | 1. Status Approval Registrasi Akun<br>2. Status Pengajuan PKL (Disetujui/Revisi/Tolak)<br>3. Notifikasi Catatan Revisi Jurnal/Laporan<br>4. Rekapitulasi Absensi & Jurnal Terkirim<br>5. Nilai Akhir & Sertifikat PKL (PDF) |
| **Guru Pembimbing** | 1. Validasi & Catatan Jurnal Harian Siswa<br>2. Status Review & Catatan Laporan Akhir<br>3. Input Komponen Nilai Akhir (Sikap, Keterampilan, Laporan)<br>4. Pesan Koordinasi ke Administrator | 1. Data Siswa Bimbingan (Profil, Absensi, Jurnal)<br>2. Notifikasi Jurnal & Laporan Baru dari Siswa<br>3. Tanggapan/Balasan Pesan Koordinasi dari Admin |
| **Pembimbing Industri** | 1. Validasi Jurnal Harian Siswa Magang<br>2. Pesan Aduan / Konsultasi Teknis & Administrasi | 1. Data & Daftar Siswa Magang di Industrinya<br>2. Rekapitulasi Kehadiran (Absensi) Siswa<br>3. Notifikasi Jurnal Harian Baru dari Siswa |
| **Administrator** | 1. CRUD Data Master (User, Guru, Siswa, Tempat PKL)<br>2. Plotting / Pemetaan Guru Pembimbing ke Siswa<br>3. Persetujuan (Approval) Registrasi Akun Mandiri<br>4. Balasan Tanggapan Pesan (Guru & Industri) | 1. Laporan Data Master (User, Tempat PKL)<br>2. Request/Notifikasi Approval User Baru<br>3. Rekap Pesan Masuk dari Guru & Industri |

---

## 3. DATA FLOW DIAGRAM (DFD) LEVEL 0: DETAIL PROSES

Pada DFD Level 0, sistem dipecah menjadi **7 proses utama** dengan melibatkan **9 data store (tabel basis data)**. Berikut adalah rincian Input, Proses, Data Store, dan Output untuk setiap sub-proses:

### Proses 1.0: Autentikasi & Registrasi
* **Deskripsi**: Menangani pendaftaran pengguna secara mandiri, login akun, serta persetujuan akun oleh Administrator.
* **Tabel Pemetaan IPO**:
  * **Input**:
    * Kredensial Login (Email & Password) &rarr; dari *Semua Entitas*.
    * Data Registrasi Akun baru &rarr; dari *Siswa, Guru, Pembimbing Industri*.
    * Persetujuan Akun (`is_approved = true`) &rarr; dari *Admin*.
  * **Proses**:
    * Memvalidasi kredensial pengguna dan status persetujuan akun.
    * Menyimpan data registrasi mandiri dengan status belum disetujui (`is_approved = false`).
    * Mengubah status menjadi disetujui (`is_approved = true`) saat diverifikasi Admin.
  * **Data Store Terkait**: `users` (Read & Write)
  * **Output**:
    * Sesi Login & Akses Dashboard &rarr; ke *Semua Entitas*.
    * Request/Notifikasi Approval Akun Baru &rarr; ke *Admin*.
    * Status Persetujuan Registrasi &rarr; ke *Siswa, Guru, Pembimbing Industri*.

### Proses 2.0: Manajemen Data Master
* **Deskripsi**: Pengelolaan data dasar sistem yang meliputi data pengguna (siswa, guru, pembimbing) dan data tempat PKL beserta kuota penempatannya.
* **Tabel Pemetaan IPO**:
  * **Input**: Data CRUD Pengguna, Tempat PKL, dan Kapasitas/Kuota &rarr; dari *Admin*.
  * **Proses**:
    * Menambah, membaca, memperbarui, atau menghapus data profil spesifik pengguna.
    * Menambah, membaca, memperbarui, atau menghapus profil instansi industri beserta kuota maksimal siswa magang.
  * **Data Store Terkait**:
    * `siswa` (Write)
    * `guru` (Write)
    * `pembimbing_industri` (Write)
    * `tempat_pkl` (Write)
  * **Output**:
    * Konfirmasi Berhasil CRUD & Laporan Data Master &rarr; ke *Admin*.

### Proses 3.0: Pengajuan & Penempatan PKL
* **Deskripsi**: Proses pengajuan lokasi PKL oleh siswa, peninjauan kuota perusahaan, persetujuan oleh guru pembimbing, dan plotting guru oleh admin.
* **Tabel Pemetaan IPO**:
  * **Input**:
    * Pilihan Tempat PKL, tanggal pelaksanaan, alasan, & berkas &rarr; dari *Siswa*.
    * Status Verifikasi (Setuju/Revisi/Tolak) & Catatan &rarr; dari *Guru*.
    * Plotting / Penugasan Guru Pembimbing (`guru_id`) &rarr; dari *Admin*.
  * **Proses**:
    * Mengecek sisa kuota tempat PKL (kuota dikurangi pengajuan berstatus `disetujui`, `sedang_pkl`, dan `menunggu_penilaian`).
    * Menyimpan draf dan mengirimkan pengajuan PKL.
    * Memperbarui status pengajuan dan mencatat guru pembimbing yang ditugaskan.
  * **Data Store Terkait**:
    * `pengajuan_pkl` (Read & Write)
    * `tempat_pkl` (Read)
  * **Output**:
    * Notifikasi Pengajuan Masuk &rarr; ke *Guru*.
    * Notifikasi Status Pengajuan PKL (Disetujui/Revisi/Tolak) &rarr; ke *Siswa*.
    * Informasi Plotting Pembimbing &rarr; ke *Siswa* & *Guru*.

### Proses 4.0: Absensi & Jurnal Harian
* **Deskripsi**: Pencatatan kehadiran harian siswa di tempat magang, pengisian kegiatan harian, serta proses validasi jurnal oleh guru pembimbing maupun pembimbing industri.
* **Tabel Pemetaan IPO**:
  * **Input**:
    * Absensi Clock-In (Waktu, Foto selfie, GPS) &rarr; dari *Siswa*.
    * Jurnal Harian (Uraian kegiatan, kendala, dokumentasi) &rarr; dari *Siswa*.
    * Status Validasi Jurnal (Valid/Revisi) & Catatan Evaluasi &rarr; dari *Guru* & *Pembimbing Industri*.
  * **Proses**:
    * Menyimpan absensi harian (membatasi hanya 1 kali clock-in per hari menggunakan indeks unik tanggal).
    * Menyimpan kegiatan jurnal harian.
    * Secara otomatis memicu transisi status pengajuan dari `disetujui` menjadi `sedang_pkl` pada jurnal pertama yang dibuat.
    * Memperbarui status validasi jurnal.
  * **Data Store Terkait**:
    * `absensi_pkl` (Read & Write)
    * `jurnal_pkl` (Read & Write)
    * `pengajuan_pkl` (Read & Write)
  * **Output**:
    * Notifikasi Jurnal Baru &rarr; ke *Guru* & *Pembimbing Industri*.
    * Rekapitulasi Absensi & Jurnal Harian &rarr; ke *Guru* & *Pembimbing Industri*.
    * Status Validasi Jurnal & Catatan Revisi &rarr; ke *Siswa*.

### Proses 5.0: Laporan Akhir PKL
* **Deskripsi**: Pengunggahan berkas laporan akhir PKL oleh siswa setelah masa magang berakhir, disertai proses peninjauan (review) oleh guru pembimbing.
* **Tabel Pemetaan IPO**:
  * **Input**:
    * Berkas PDF Laporan Akhir &rarr; dari *Siswa*.
    * Status Review Laporan (Diterima/Revisi) & Catatan &rarr; dari *Guru*.
  * **Proses**:
    * Menyimpan file laporan akhir.
    * Memperbarui status laporan.
    * Jika laporan berstatus `diterima`, otomatis mengubah status pengajuan PKL menjadi `menunggu_penilaian`.
  * **Data Store Terkait**:
    * `laporan_pkl` (Read & Write)
    * `pengajuan_pkl` (Read & Write)
  * **Output**:
    * Notifikasi Laporan Baru & File Laporan Akhir &rarr; ke *Guru*.
    * Status Review Laporan & Catatan Perbaikan &rarr; ke *Siswa*.

### Proses 6.0: Penilaian & Sertifikasi
* **Deskripsi**: Proses pengisian nilai evaluasi siswa oleh guru pembimbing, perhitungan nilai akhir secara otomatis, serta penerbitan sertifikat PKL.
* **Tabel Pemetaan IPO**:
  * **Input**:
    * Komponen Nilai (Sikap, Keterampilan, Laporan) & Catatan Evaluasi &rarr; dari *Guru*.
    * Permintaan (Request) Unduh Sertifikat &rarr; dari *Siswa*.
  * **Proses**:
    * Mengkalkulasi Nilai Akhir dengan rumus: Rata-rata dari Nilai Sikap, Nilai Keterampilan, dan Nilai Laporan.
    * Menyimpan data penilaian dan mengubah status pengajuan PKL menjadi `selesai`.
    * Membuka akses unduhan berkas sertifikat digital.
  * **Data Store Terkait**:
    * `penilaian_pkl` (Read & Write)
    * `pengajuan_pkl` (Read & Write)
  * **Output**:
    * Nilai Akhir & Rekapitulasi Skor &rarr; ke *Siswa*.
    * Berkas Sertifikat PKL (PDF) &rarr; ke *Siswa*.

### Proses 7.0: Komunikasi & Pesan
* **Deskripsi**: Fasilitas bertukar pesan/aduan dari pihak industri kepada sekolah, koordinasi guru ke admin, serta tanggapan balasan dari sekolah/admin.
* **Tabel Pemetaan IPO**:
  * **Input**:
    * Pesan Hubungi Sekolah (Kategori: administrasi, kendala, teknis) &rarr; dari *Pembimbing Industri*.
    * Pesan Hubungi Admin (Kategori: penilaian, jurnal, laporan, teknis) &rarr; dari *Guru*.
    * Teks Balasan / Tanggapan &rarr; dari *Admin*.
  * **Proses**:
    * Menyimpan pesan masuk dan mengkategorikannya.
    * Menyimpan balasan tanggapan admin dan memperbarui status tiket aduan menjadi selesai.
  * **Data Store Terkait**:
    * `pesan_pembimbing` (Read & Write)
    * `pesan_guru` (Read & Write)
  * **Output**:
    * Notifikasi & Isi Pesan Masuk &rarr; ke *Admin* & *Guru*.
    * Tanggapan Balasan Pesan &rarr; ke *Guru* & *Pembimbing Industri*.

---

## 4. STRUKTUR DATA STORE (TABEL) UNTUK DFD

Dalam merancang DFD, kotak bergaris ganda atau simbol tabung mewakili Data Store. Berikut adalah daftar tabel basis data yang dijadikan representasi data store:

1. **`users`**: Menyimpan kredensial autentikasi, role (`admin`, `guru`, `siswa`, `pembimbing_industri`), dan status verifikasi akun (`is_approved`).
2. **`siswa`**: Menyimpan biodata profil siswa seperti NIS, kelas, jurusan, alamat, dan nomor HP.
3. **`guru`**: Menyimpan data NIP, alamat, dan nomor HP guru pembimbing.
4. **`tempat_pkl`**: Menyimpan profil instansi/perusahaan, bidang usaha, dan kuota penerimaan siswa magang.
5. **`pembimbing_industri`**: Menyimpan data staff industri yang terasosiasi dengan tempat PKL tertentu.
6. **`pengajuan_pkl`**: Menyimpan berkas pengajuan, tanggal mulai/selesai PKL, guru pembimbing yang ditugaskan, dan siklus status magang siswa.
7. **`absensi_pkl`**: Menyimpan data absensi harian (tanggal, jam masuk, titik koordinat GPS, dan foto selfie).
8. **`jurnal_pkl`**: Menyimpan uraian kegiatan harian, kendala, dokumentasi foto kegiatan, dan status validasi.
9. **`laporan_pkl`**: Menyimpan unggahan dokumen laporan akhir beserta status review-nya.
10. **`penilaian_pkl`**: Menyimpan akumulasi nilai sikap, keterampilan, laporan, serta rata-rata nilai akhir.
11. **`pesan_pembimbing`** & **`pesan_guru`**: Menyimpan aduan, koordinasi pesan, dan respons tanggapan dari admin.

---

> [!TIP]
> **Tips untuk Menggambar DFD:**
> * **Diagram Konteks**: Gambarlah 1 lingkaran besar di tengah bertuliskan *"SISTEM INFORMASI PKL (SiPKL)"*. Letakkan 4 kotak Entitas Luar di sekelilingnya, dan hubungkan dengan anak panah aliran data sesuai dengan tabel di **Bab 2** laporan ini.
> * **DFD Level 0**: Gambarlah 7 lingkaran proses di dalam sistem. Hubungkan aliran data dari entitas luar ke proses yang sesuai (misal: Siswa mengirim absensi ke *Proses 4.0*). Gambarkan juga aliran data dari proses menuju Data Store terkait (misal: *Proses 4.0* menulis data ke Data Store *absensi_pkl*).
