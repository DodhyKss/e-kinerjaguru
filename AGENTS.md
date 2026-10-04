# AGENTS.md

Laravel 13 (PHP 8.3) + Tailwind 4 / Vite 8 app for Indonesian teacher performance evaluation (*e-kinerja guru*). The README.md is still the stock Laravel scaffold README — ignore it.

## Commands

- `composer dev` — runs `artisan serve` + `queue:listen` + `pail` + `vite` together. Use this, not bare `php artisan serve`.
- `composer test` — clears config then `php artisan test`.
- Single test: `php artisan test --filter=SomeTestName` / path: `php artisan test tests/Feature/FooTest.php`
- Format: `./vendor/bin/pint` (Laravel Pint, no pint.json — use defaults).
- Frontend: `npm run dev` / `npm run build`. `public/build/` is **committed**, so run `npm run build` and include the asset diff whenever you change `resources/css/app.css` or `resources/js/app.js`.
- Migrations: `php artisan migrate`. Seed: `php artisan db:seed` (dimensions, indicators, demo users).

## Environment / Docker

- **There is no host PHP.** Everything runs in the container: `docker compose exec -T app <cmd>`. Same for `./vendor/bin/pint` and `php artisan test`.
- `.env.example` ships a real `APP_KEY` and MySQL host `db` (docker-compose service). Local non-docker runs need `DB_HOST` changed to `127.0.0.1`.
- `.env.example` contains a real Hostinger credential block — it is gitignored (`.env` is), so never copy those values into committed files.
- `docker compose up` builds php:8.3-apache, serves the app on :8000 and MySQL on :3306 (`root`/`root`, db `e_kinerjaguru`). `docker-entrypoint.sh` auto-runs `composer install`, `npm install`, `npm run build`, `key:generate`, and retries `migrate` 15× while the DB boots.
- Tests use sqlite `:memory:` (see `phpunit.xml`) and never touch the dev MySQL DB. **Verify schema-changing migrations against both**: `php artisan migrate` on MySQL and the test suite on sqlite (an enum `->change()` behaves differently).
- `tests/Feature/ExampleTest.php` fails out of the box (`/` redirects to login = 302, stock test asserts 200). Pre-existing — not a regression signal.

## Domain / architecture notes

- Roles are `admin` (a.k.a. **Admin Pusat**), `admin_internal` (**Admin Internal Sekolah**), `guru`, `penilai`, `kepala_sekolah`. `users.role` is a **MySQL ENUM** — adding a role requires a migration.
- Authorization is via the `role:` middleware alias (`app/Http/Middleware/RoleMiddleware.php`, registered in `bootstrap/app.php`).
- **Non-obvious:** `User::hasRole()` (`app/Models/User.php`) does not only read `users.role` — for `guru`/`penilai`/`kepala_sekolah` it returns true if the user has a related row in the respective table, regardless of the `role` column. `role:admin` and `role:admin_internal` are pure column checks.
- `admin_internal` is **school-scoped via `users.school_id`**. The rules live in two places, keep them in sync:
  - `ScopesSchoolData` trait (`app/Http/Controllers/Concerns/`) — `authorizeSchoolManagement()`, `managedSchoolId()`, `authorizeRecordSchool()`, `resolveSchoolId()`, `applySchoolScope()`. `resolveSchoolId()` is what stops a crafted `school_id` in the request body from targeting another school. Never validate `school_id` from input for this role.
  - `User::isManageableBy()` — the single rule for "may this actor reset/toggle this account"; used by both `UserController` and `resources/views/users/index.blade.php`.
- `admin_internal` can manage guru/penilai and reset accounts in its **own school only**, and can **read-only** browse its evaluations. It cannot create penugasan, approve, or submit. Only `admin` can create another `admin_internal` account.
- `MonitoringController` aggregates along `guru → school → kabupaten → provinsi`. **`schools.provinsi_id` and `schools.kabupaten_id` are not guaranteed to be consistent**, so every wilayah filter resolves province via `kabupaten.provinsi_id` — see `FiltersWilayah` trait. Don't "simplify" this to `schools.provinsi_id`.
- `gurus`/`penilais` have no geography columns of their own; both hang off `school_id`. `Penilai::count()` double-counts kepala sekolah because kepsek are mirrored into `penilais` (`jabatan = 'Kepala Sekolah'`).
- **Central admin evidence rewrite:** `saveIndicatorForm()` has **no status guard** — `admin` can rewrite `completed`/`approved` evaluations. Audit columns on `evaluation_results` (`updated_by_user_id`, `updated_by_name`, `edited_at`) are written **only when the actor is `admin`**, so "filled by central admin" stays distinguishable from "filled by assessor". `edited_at` doubles as the "was edited by admin" flag (`EvaluationResult::wasEditedByAdmin()`). `submit()` is deliberately *not* open to `admin`.
- All routes live in one file, `routes/web.php`, with role gates inline. Literal `/rekomendasi`-style routes must be declared **before** `/{evaluation}` wildcards inside the `evaluations` prefix or they get captured as params.
- Uploads bypass the `local` filesystem disk and are moved straight to `public_path('dokumen_guru')` (`EvaluationController`), storing a relative `dokumen_guru/...` string in `DocumentReviewData.file_path`. `public/dokumen_guru` is gitignored. Uploads stay **guru-only** — the central admin edits evidence *text*, never files.
- Blade views are plain (no Vue/React/Livewire). jQuery, Select2 (4.1.0-rc.0), and Lucide are loaded from CDNs in `resources/views/layouts/app.blade.php`, not via npm. Select2 styling is hand-rolled in `resources/css/app.css` — Tailwind 4 with `@import 'tailwindcss'` and `@theme` (no `tailwind.config.js`).
- `resources/js/app.js` is essentially empty; all interactivity lives inline in Blade `@push('scripts')` blocks.
- Wide tables need a wrapper `overflow-x-auto` plus a `min-w-[...]` on the `<table>`; a bare `whitespace-nowrap` table inside `overflow-hidden` gets clipped instead of scrolling.

## Conventions

- Code comments and UI strings are in Indonesian; keep new ones consistent with the surrounding file.
- 4-space indent, LF, final newline (`.editorconfig`).
- Commit messages follow Conventional Commits in Indonesian, e.g. `feat: tambah filter sekolah di user index`, `fix bug double penilais` (both forms appear in history).
- `revisi.md` at the repo root holds the current task/requirement list — check it for in-flight work.