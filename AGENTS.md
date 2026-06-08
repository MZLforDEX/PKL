# AGENTS.md

Laravel 12 SiPKL (Sistem Informasi Praktik Kerja Lapangan) — Breeze auth, role middleware, 14 models, role-grouped controllers/views/routes. `.env` uses **MySQL** (`project_pkl_v5.2`), APP_NAME=`"SiPKL"`.

**59 tests passing** — run via `composer run test`.

---

## Commands

```bash
composer run setup       # composer install + .env + key:generate + migrate + npm install && build
composer run dev         # serve + queue:listen + pail + vite (concurrent)
composer run test        # config:clear then php artisan test
php artisan storage:link # symlink for file uploads (needed after clone)
php artisan migrate --seed
```

## Non-default config

- `timezone` → `Asia/Makassar`, `locale` → `id`, `faker_locale` → `id_ID`
- `.env.example` defaults to SQLite; actual `.env` uses MySQL
- `QUEUE_CONNECTION=database`, `SESSION_DRIVER=database`, `CACHE_STORE=database`
- Tests override to `array` (cache/session), `sync` (queue), SQLite in-memory — see `phpunit.xml`

## Roles

| Role | DB value | Route prefix |
|------|----------|-------------|
| Admin | `admin` | `/admin` |
| Guru | `guru` | `/guru` |
| Siswa | `siswa` | `/siswa` |
| Pembimbing industri | `pembimbing_industri` | `/pembimbing` |

Public: `/` (welcome), `/guide`. Notifications (database channel): `/notifications` — all auth roles.

## Architecture

**Controllers** under `app/Http/Controllers/{Admin,Guru,Siswa,PembimbingIndustri}/`. Shared: `ProfileController`, `NotificationController`. Middleware alias `role` → `RoleMiddleware` (single-role string match, abort 403) registered in `bootstrap/app.php`.

**Models** (14): `User`, `Siswa`, `Guru`, `TempatPkl`, `PembimbingIndustri`, `PengajuanPkl`, `JurnalPkl`, `LaporanPkl`, `PenilaianPkl`, `AbsensiPkl`, `PesanPembimbing`, `PesanGuru`, `PesanPembimbingReply`, `PesanGuruReply`. All use `$fillable`.

**Routes** in `routes/web.php` — role groupings via `->middleware(['auth', 'role:...'])`. Auth routes are default Breeze — do not modify.

**Login redirection** (`AuthenticatedSessionController@store`): role-based → `{admin,guru,siswa,pembimbing}.dashboard`. Unapproved users (`is_approved = false`) rejected at login.

## Status Workflows

| Entity | States |
|--------|--------|
| Pengajuan | `draft` → `menunggu_persetujuan` → `revisi` / `disetujui` / `ditolak` → `sedang_pkl` → `menunggu_penilaian` → `selesai` |
| Jurnal | `menunggu_validasi` → `valid` / `revisi` |
| Laporan | `menunggu_review` → `diterima` / `revisi` |

Key transitions: Siswa submits → guru approves/rejects/requests revision. First jurnal created sets `disetujui` → `sedang_pkl`. Guru accepts laporan → `menunggu_penilaian`. Guru saves penilaian → `selesai`.

## Business Rules

- **Siswa**: one active pengajuan (excl. `ditolak`, `selesai`). Edit/delete only `draft`/`revisi` (delete also `ditolak`). `authorizeOwner()` on PengajuanPkl & JurnalPkl controllers. Absensi: clock-in once/day during PKL. Sertifikat: print only when `selesai`.
- **Guru**: only assigned students (`guru_id`). `authorizeBimbingan()`. Checks quota when approving.
- **Admin**: CRUD master data + assign `guru_id` on pengajuan + approve `is_approved`. Admin-created accounts auto-approved.
- **Pembimbing industri**: tied to `tempat_pkl_id`. Validates jurnal (parallel with guru). Views absensi.
- **Quota** (`TempatPkl`): counts status `disetujui`, `sedang_pkl`, `menunggu_penilaian`. Accessors: `sisa_kuota`, `is_penuh`.
- **Penilaian**: `nilai_akhir = round((nilai_sikap + nilai_keterampilan + nilai_laporan) / 3, 2)`. Validates `menunggu_penilaian` status before save.
- **Messaging**: `PesanPembimbing` + `PesanPembimbingReply` (pembimbing → admin/guru), `PesanGuru` + `PesanGuruReply` (guru → admin).

## Notifications (database + mail)

| Event | Recipient | Class |
|-------|-----------|-------|
| Teacher changes pengajuan status | Siswa | `PengajuanPklStatusChanged` |
| Student uploads jurnal | Guru | `SiswaUploadJurnal` |
| Student uploads/resubmits laporan | Guru | `SiswaUploadLaporan` |
| Pembimbing sends message | Admin/guru | `PesanBaruDariIndustri` |
| Guru sends message to admin | Admin | `PesanBaruDariGuru` |
| Admin replies to pembimbing | Pembimbing | `PesanTelahDibalasSekolah` |
| Admin replies to guru | Guru | `PesanGuruDibalas` |

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
| `HubungiSekolahTest` | Messaging: pembimbing→admin/guru, guru→admin |
| `PenilaianPklTest` | Penilaian create/store, authorization, status guard |
| `Auth/*` + `ProfileTest` | Default Breeze auth + profile |

Tests use SQLite in-memory. No external services needed.

## Gotchas

- `README.md` is the default Laravel skeleton — unrelated.
- **Blade + Tailwind v3 (PostCSS) + Alpine.js** — no React/Vue/Inertia. `@tailwindcss/vite` v4 is installed but **unused**; don't add v4 directives.
- Custom theme in `tailwind.config.js`: `brand-50`–`950`, `surface-50`–`950`, shadows (`glass`, `neon`, `card`), animations (`fade-in`, `slide-up`, etc.).
- Lucide icons via CDN (`unpkg.com/lucide@latest`). Call `lucide.createIcons()` after DOM updates. Font Awesome 6.4 (`cdnjs.cloudflare.com`) also available.
- SweetAlert2 via CDN — global `confirmAction(title, text, icon, confirmText, callback)` helper in `resources/views/layouts/app.blade.php`.
- UI language: Indonesian.
- `QUEUE_CONNECTION=database` — queue listener (`composer run dev`) needed for notifications.
- Dark mode: `class` + JS (`localStorage`) + `!important` CSS overrides in `resources/css/app.css`. Tailwind `dark:` variants may be overridden.
- Font mismatch: `tailwind.config.js` references Plus Jakarta Sans / Outfit but `app.blade.php` loads Inter with inline `font-family: 'Inter'`.
- Route model binding `laporan/{laporanPkl}` must be defined **after** `laporan/create` (already correct in current routes).
- No `opencode.json` — config lives solely in `AGENTS.md`.
