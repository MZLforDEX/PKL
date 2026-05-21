# AGENTS.md

## Status

Full Laravel 12 PKL system — **all features built out**. Breeze auth installed, role middleware registered, all migrations/models/controllers/views/routes created, seeder ready. `.env` uses MySQL (`project_pkl_v5.2`). `DESIGN.md` and `README.md` are leftover templates — ignore.

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

Sistem Informasi Pengajuan PKL SMK — 3 roles (`admin`, `guru`, `siswa` in `users.role`).

### Controller Map

```
Admin/  -> Dashboard, Siswa, Guru, TempatPkl, PengajuanPkl
Guru/   -> Dashboard, PengajuanPkl, JurnalPkl, LaporanPkl, PenilaianPkl
Siswa/  -> Dashboard, PengajuanPkl, JurnalPkl, LaporanPkl
```

### Status Workflow

| Entity | Statuses |
|---|---|
| Pengajuan | `draft` → `menunggu_persetujuan` → `revisi`/`disetujui`/`ditolak` → `sedang_pkl` → `menunggu_penilaian` → `selesai` |
| Jurnal | `menunggu_validasi` → `valid`/`revisi` |
| Laporan | `menunggu_review` → `diterima`/`revisi` |

### File Uploads

| Type | Folder | Rules |
|---|---|---|
| Dokumen pengajuan | `storage/app/public/pengajuan/` | pdf,doc,docx | max:2048 |
| Dokumentasi jurnal | `storage/app/public/jurnal/` | jpg,jpeg,png,pdf | max:2048 |
| Laporan akhir | `storage/app/public/laporan/` | pdf | max:5120 |

### Business Rules

- Siswa: only 1 active pengajuan at a time. Edit only if status `draft` or `revisi`. Uses `authorizeOwner()` helper.
- Guru: only sees siswa bimbingan (where `guru_id` matches). Uses `authorizeBimbingan()` helper.
- Admin: sets `guru_id` on pengajuan. CRUD all master data. Not the primary validator.
- Penilaian: `nilai_akhir = (nilai_sikap + nilai_keterampilan + nilai_laporan) / 3`. Sets pengajuan status to `selesai`.
- Language: UI in Bahasa Indonesia.

### Login Defaults

| Role | Email | Password |
|---|---|---|
| admin | admin@pkl.test | password |
| guru | guru1@pkl.test, guru2@pkl.test | password |
| siswa | siswa1@pkl.test ... siswa5@pkl.test | password |

---

## Gotchas

- `DESIGN.md` and `README.md` are **leftover templates** — unrelated to this project.
- Tailwind **v3** via PostCSS (`tailwind.config.js` + `postcss.config.js`), NOT v4. `@tailwindcss/vite` v4 package is installed but **unused**.
- Use `$fillable` on all models + Eloquent relationships + eager loading mandatory.
- No React/Vue/Inertia — Blade + Tailwind only. Babel/TypeScript not configured.
- Lucide icons loaded via CDN (`unpkg.com/lucide@latest`). Run `lucide.createIcons()` after dynamic DOM updates.
- Auth routes (login, register, password reset, email verification) are default Breeze — do not change.
- No `opencode.json` — config lives solely in `AGENTS.md`.
