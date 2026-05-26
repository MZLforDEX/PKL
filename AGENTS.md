# AGENTS.md

## Status

Full Laravel 12 PKL system — **all features built out**. Breeze auth installed, role middleware registered, all migrations/models/controllers/views/routes created, seeder ready. `.env` uses MySQL (`project_pkl_v5.2`). `README.md` is the default Laravel skeleton template — unrelated.

**47 feature tests passing** (run via `composer run test`).

---

## Commands

```bash
# Full setup (after clone)
composer run setup

# Dev servers (PHP + queue + logs + Vite concurrently)
composer run dev

# Run tests (clears config first)
composer run test

# Vite only
npm run dev
npm run build

# Manual steps
php artisan storage:link          # symlink for file uploads
php artisan migrate --seed        # if not via setup
php artisan serve                 # standalone dev server
```

---

## Project Spec (PKL System)

Sistem Informasi Pengajuan PKL SMK — **4 roles** in `users.role`:

| Role | Value | Prefix route |
|------|-------|--------------|
| Admin | `admin` | `/admin` |
| Guru (pendamping sekolah) | `guru` | `/guru` |
| Siswa | `siswa` | `/siswa` |
| Pembimbing industri | `pembimbing_industri` | `/pembimbing` |

Halaman publik: `/` (welcome), `/guide` (panduan penggunaan).

Notifikasi in-app (Laravel notifications): `/notifications` — semua role yang login.

### Controller Map

```
Admin/
  -> Dashboard, Siswa, Guru, TempatPkl, PembimbingIndustri,
     PengajuanPkl, User (approval registrasi)

Guru/
  -> Dashboard, PengajuanPkl, JurnalPkl, LaporanPkl,
     PenilaianPkl, AbsensiPkl

Siswa/
  -> Dashboard, PengajuanPkl, JurnalPkl, LaporanPkl,
     AbsensiPkl (+ cetak sertifikat)

PembimbingIndustri/
  -> Dashboard, SiswaBimbingan, JurnalPkl, AbsensiPkl

Shared (auth middleware)
  -> ProfileController, NotificationController
```

### Status Workflow

| Entity | Statuses |
|--------|----------|
| Pengajuan | `draft` → `menunggu_persetujuan` → `revisi` / `disetujui` / `ditolak` → `sedang_pkl` → `menunggu_penilaian` → `selesai` |
| Jurnal | `menunggu_validasi` → `valid` / `revisi` |
| Laporan | `menunggu_review` → `diterima` / `revisi` (siswa dapat upload ulang saat `revisi`) |

**Transisi penting pengajuan:**

- Siswa ajukan: `draft`/`revisi` → `menunggu_persetujuan`
- Guru setujui/tolak/revisi: dari `menunggu_persetujuan`
- Jurnal pertama siswa dibuat: `disetujui` → `sedang_pkl`
- Guru terima laporan: pengajuan → `menunggu_penilaian`
- Guru simpan penilaian: pengajuan → `selesai`

### File Uploads

| Type | Folder | Rules |
|------|--------|-------|
| Dokumen pengajuan | `storage/app/public/pengajuan/` | pdf, doc, docx — max 2048 KB |
| Dokumentasi jurnal | `storage/app/public/jurnal/` | jpg, jpeg, png, pdf — max 2048 KB |
| Laporan akhir | `storage/app/public/laporan/` | pdf — max 5120 KB |

### Business Rules

**Siswa**

- Hanya **1 pengajuan aktif** (status selain `ditolak` dan `selesai`).
- Edit/hapus pengajuan: hanya `draft` atau `revisi` (hapus juga `ditolak`).
- `authorizeOwner()` di `PengajuanPklController` dan `JurnalPklController`.
- Jurnal: edit jika belum `valid`; update mengembalikan status ke `menunggu_validasi`.
- Laporan: upload pertama saat pengajuan `disetujui`/`sedang_pkl` dan belum punya laporan; **edit/upload ulang** hanya saat laporan berstatus `revisi` → kembali `menunggu_review`, notifikasi ke guru.
- Absensi: clock-in sekali per hari saat PKL aktif.
- Sertifikat: cetak hanya jika pengajuan `selesai`.

**Guru**

- Hanya siswa bimbingan (`guru_id` cocok). `authorizeBimbingan()` di controller guru.
- Validasi pengajuan, jurnal, laporan; input penilaian.
- Kuota tempat PKL dicek saat menyetujui pengajuan (sama seperti siswa saat ajukan).

**Admin**

- CRUD master: siswa, guru, tempat PKL, pembimbing industri.
- Assign `guru_id` pada pengajuan (bukan validator utama pengajuan).
- Approve/hapus user registrasi (`is_approved`).
- Akun dibuat admin (siswa/guru/pembimbing) langsung `is_approved = true`.

**Pembimbing industri**

- Terikat ke `tempat_pkl_id`; melihat siswa pengajuan di tempat tersebut (status aktif/selesai).
- Dapat validasi/revisi jurnal (paralel dengan guru).
- Melihat absensi siswa bimbingan.

**Umum**

- Penilaian: `nilai_akhir = round((nilai_sikap + nilai_keterampilan + nilai_laporan) / 3, 2)` → status pengajuan `selesai`.
- Registrasi publik: login ditolak sampai admin set `is_approved` (`AuthenticatedSessionController`).
- Kuota `TempatPkl`: dihitung dari pengajuan berstatus `disetujui`, `sedang_pkl`, `menunggu_penilaian` (`is_penuh` accessor).
- UI: Bahasa Indonesia.

### Notifications

| Event | Penerima | Class |
|-------|----------|-------|
| Status pengajuan berubah (guru) | Siswa | `PengajuanPklStatusChanged` |
| Siswa upload jurnal | Guru | `SiswaUploadJurnal` |
| Siswa upload / kirim ulang laporan | Guru | `SiswaUploadLaporan` |

### Login Defaults (seeder)

| Role | Email | Password | Catatan |
|------|-------|----------|---------|
| admin | admin@pkl.test | password | |
| guru | guru1@pkl.test, guru2@pkl.test | password | |
| siswa | siswa1@pkl.test … siswa5@pkl.test | password | |
| pembimbing industri | pembimbing@pkl.test | password | Terhubung ke PT Teknologi Maju |

---

## Tests

| File | Cakupan |
|------|---------|
| `AbsensiPklTest` | Clock-in siswa, lihat absensi guru/pembimbing |
| `LaporanPklRevisiTest` | Edit/upload ulang laporan revisi, otorisasi |
| `NotificationSystemTest` | Notifikasi pengajuan, jurnal, laporan |
| `PembimbingIndustriTest` | CRUD admin, validasi jurnal pembimbing |
| `PengajuanPklQuotaTest` | Kuota tempat PKL |
| `Auth/*`, `ProfileTest` | Breeze auth & profil |

---

## Gotchas

- `README.md` is the **default Laravel skeleton** — unrelated to this project.
- Tailwind **v3** via PostCSS (`tailwind.config.js` + `postcss.config.js`), NOT v4. `@tailwindcss/vite` v4 package is installed but **unused**.
- Use `$fillable` on all models + Eloquent relationships + eager loading mandatory.
- No React/Vue/Inertia — Blade + Tailwind only. Babel/TypeScript not configured.
- Views use Blade component pattern: `<x-app-layout>` with `<x-slot name="header">` and `{{ $slot }}`.
- Tailwind theme includes custom color scales (`brand-50`–`950`, `surface-50`–`950`) and custom shadows (`glass`, `neon`, `card`). Check `tailwind.config.js` before picking colors.
- Lucide icons loaded via CDN (`unpkg.com/lucide@latest`). Run `lucide.createIcons()` after dynamic DOM updates.
- SweetAlert2 loaded via CDN (`cdn.jsdelivr.net/npm/sweetalert2@11`). Global `confirmAction()` helper available in `resources/views/layouts/app.blade.php` for confirmation dialogs.
- Auth routes (login, register, password reset, email verification) are default Breeze — do not change.
- Route model binding laporan siswa: `laporan/{laporanPkl}` — harus didefinisikan setelah `laporan/create` agar tidak bentrok.
- Penilaian guru belum memvalidasi status `menunggu_penilaian` secara eksplisit (bisa ditambah jika diperlukan).
- No `opencode.json` — config lives solely in `AGENTS.md`.
