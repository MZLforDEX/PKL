# AGENTS.md

Laravel 12 PKL (Praktik Kerja Lapangan) system — **complete with all features built out**. Breeze auth, role middleware, migrations, models, controllers, views, routes, seeder. `.env` uses **MySQL** (`project_pkl_v5.2`), APP_NAME=`SPARTA`.

**46 feature tests passing** — run via `composer run test`.

---

## Commands

```bash
composer run setup       # full install: composer install + .env + key:generate + migrate + npm install && build
composer run dev         # concurrent: serve + queue:listen + pail (logs) + vite
composer run test        # config:clear then php artisan test
php artisan storage:link # symlink for file uploads (needed after clone)
php artisan migrate --seed
```

## Roles

| Role | DB value | Route prefix |
|------|----------|-------------|
| Admin | `admin` | `/admin` |
| Guru | `guru` | `/guru` |
| Siswa | `siswa` | `/siswa` |
| Pembimbing industri | `pembimbing_industri` | `/pembimbing` |

Public pages: `/` (welcome), `/guide`. Notifications (in-app, database channel): `/notifications` — all authenticated roles.

## Architecture

**Controllers** grouped by role under `app/Http/Controllers/{Admin,Guru,Siswa,PembimbingIndustri}/`. Shared: `ProfileController`, `NotificationController`. Middleware: `role` alias registered in `bootstrap/app.php` → `RoleMiddleware` (single-role string match, abort 403).

**Models** (10): `User`, `Siswa`, `Guru`, `TempatPkl`, `PembimbingIndustri`, `PengajuanPkl`, `JurnalPkl`, `LaporanPkl`, `PenilaianPkl`, `AbsensiPkl`. All use `$fillable`.

**Routes** defined in `routes/web.php` — role groupings via `->middleware(['auth', 'role:...'])`. Auth routes are default Breeze — do not modify.

**Login redirection** (`AuthenticatedSessionController@store`): role-based → `{admin,guru,siswa,pembimbing}.dashboard`. Unapproved users (`is_approved = false`) are rejected at login.

## Status Workflows

| Entity | States |
|--------|--------|
| Pengajuan | `draft` → `menunggu_persetujuan` → `revisi` / `disetujui` / `ditolak` → `sedang_pkl` → `menunggu_penilaian` → `selesai` |
| Jurnal | `menunggu_validasi` → `valid` / `revisi` |
| Laporan | `menunggu_review` → `diterima` / `revisi` |

Key transitions:
- Siswa submits: `draft`/`revisi` → `menunggu_persetujuan`
- Guru approves/rejects/requests revision
- First jurnal created: `disetujui` → `sedang_pkl`
- Guru accepts laporan: `sedang_pkl` → `menunggu_penilaian`
- Guru saves penilaian: `menunggu_penilaian` → `selesai`

## Business Rules

- **Siswa**: one active pengajuan (excludes `ditolak`, `selesai`). Edit/delete only `draft`/`revisi` (delete also `ditolak`). `authorizeOwner()` on PengajuanPkl & JurnalPkl controllers. Absensi: clock-in once/day during PKL. Sertifikat: print only when `selesai`.
- **Guru**: only assigned students (`guru_id`). `authorizeBimbingan()` on guru controllers. Checks quota when approving.
- **Admin**: CRUD master data + assign `guru_id` on pengajuan + approve user registrations (`is_approved`). Admin-created accounts auto-approved.
- **Pembimbing industri**: tied to `tempat_pkl_id`. Validates jurnal (parallel with guru). Views absensi.
- **Quota** (`TempatPkl`): counts pengajuan with status `disetujui`, `sedang_pkl`, `menunggu_penilaian`. Exposed via `sisa_kuota` and `is_penuh` accessors.
- **Penilaian**: `nilai_akhir = round((nilai_sikap + nilai_keterampilan + nilai_laporan) / 3, 2)` → sets pengajuan `selesai`. Validates `menunggu_penilaian` status before saving (redirects back with error if wrong).

## Notifications (database + mail)

| Event | Recipient | Class |
|-------|-----------|-------|
| Teacher changes pengajuan status | Student | `PengajuanPklStatusChanged` |
| Student uploads jurnal | Guru | `SiswaUploadJurnal` |
| Student uploads/resubmits laporan | Guru | `SiswaUploadLaporan` |

## File Uploads

| Type | Storage path | Rules |
|------|-------------|-------|
| Dokumen pengajuan | `storage/app/public/pengajuan/` | pdf,doc,docx — 2048 KB max |
| Jurnal dokumentasi | `storage/app/public/jurnal/` | jpg,jpeg,png,pdf — 2048 KB max |
| Laporan akhir | `storage/app/public/laporan/` | pdf — 5120 KB max |

## Login Defaults (seeder)

All password: `password`

| Role | Email |
|------|-------|
| admin | admin@pkl.test |
| guru | guru1@pkl.test, guru2@pkl.test |
| siswa | siswa1@pkl.test … siswa5@pkl.test |
| pembimbing | pembimbing@pkl.test |

## Tests

| File | Covers |
|------|--------|
| `AbsensiPklTest` | Clock-in, absensi views per role |
| `LaporanPklRevisiTest` | Re-upload on revision, authorization |
| `NotificationSystemTest` | Notifications on pengajuan/jurnal/laporan events |
| `PembimbingIndustriTest` | Admin CRUD, jurnal validation |
| `PengajuanPklQuotaTest` | Quota enforcement |
| `Auth/*`, `ProfileTest` | Breeze auth + profile |

Tests use SQLite in-memory (`phpunit.xml`). No external services needed.

## Gotchas

- `README.md` is the **default Laravel skeleton** — unrelated.
- Tailwind **v3** (PostCSS). `@tailwindcss/vite` v4 is installed but **unused**. Don't add v4 directives.
- Custom theme in `tailwind.config.js`: `brand-50`–`950`, `surface-50`–`950`, shadows (`glass`, `neon`, `card`), animations (`fade-in`, `slide-up`, etc.). Check before picking colors.
- No React/Vue/Inertia — **Blade + Tailwind + Alpine.js**. Alpine used for interactivity (`x-data`, `x-show`, `x-cloak`, `x-transition`).
- Lucide icons via CDN (`unpkg.com/lucide@latest`). Must call `lucide.createIcons()` after DOM updates. Font Awesome 6.4 (`cdnjs.cloudflare.com`) also available.
- SweetAlert2 via CDN — global `confirmAction(title, text, icon, confirmText, callback)` helper in `app.blade.php`.
- Route model binding `laporan/{laporanPkl}` must be defined **after** `laporan/create` (already correct in current routes).
- Views use Blade component pattern: `<x-app-layout>` with `<x-slot name="header">` and `{{ $slot }}`.
- UI language: Indonesian.
- `QUEUE_CONNECTION=database` — notifications + job tables used. Queue listener is part of `composer run dev`.
- `SESSION_DRIVER=database`, `CACHE_STORE=database` — non-default (usually `file`). Tests override these to `array` and `sync` respectively.
- Font mismatch: `tailwind.config.js` references Plus Jakarta Sans / Outfit but `app.blade.php` loads Inter from Google Fonts with inline `font-family: 'Inter'`.
- No `opencode.json` — config lives solely in `AGENTS.md`.
