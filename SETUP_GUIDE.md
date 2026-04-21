# HalenMiaga — setup guide

This document walks through a full local (or Docker) setup. For a short overview, see [`README.md`](README.md).

## Prerequisites

- **PHP 8.2+** with extensions: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo` (and **GD** if you process images the same way as production).
- **Composer** (PHP dependency manager).
- **MySQL 8** (or compatible).
- **Node.js** (optional) if you build front-end assets with Vite; not required for basic PHP/Blade operation.

---

## 1. Get dependencies

From the **repository root**:

```bash
composer install
```

From the **Laravel app**:

```bash
cd laravel
composer install
cd ..
```

The root `composer install` is for **Phinx** (database migrations at repo level). The `laravel/` install is the main application.

---

## 2. Create the database

Create an empty database (example name **`bag_biz`**) with **utf8mb4** collation.

Point Phinx and Laravel at the same database so schema from Phinx matches what Eloquent expects.

---

## 3. Configure environment variables

Phinx reads the **repository root** `.env`. Laravel reads **`laravel/.env`**. Use the **same MySQL database** in both so Phinx schema and Laravel migrations stay consistent.

### 3a. Root `.env` (Phinx)

1. Copy:

   ```bash
   copy .env.example .env
   ```

   On macOS/Linux: `cp .env.example .env`

2. Set **`BAG_DB_HOST`**, **`BAG_DB_USER`**, **`BAG_DB_PASS`**, **`BAG_DB_NAME`** to your MySQL instance (see comments in [`.env.example`](.env.example) for Docker vs local).

### 3b. Laravel `laravel/.env`

1. Copy:

   ```bash
   copy laravel\.env.example laravel\.env
   ```

   On macOS/Linux: `cp laravel/.env.example laravel/.env`

2. Generate an app key:

   ```bash
   cd laravel
   php artisan key:generate
   ```

3. Edit **`laravel/.env`** and set at least:

   | Variable | Purpose |
   |----------|---------|
   | `APP_URL` | Base URL users open in the browser (e.g. `http://localhost:8080`). Must match Stripe return URLs. |
   | `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | MySQL connection (same logical database as **`BAG_DB_*`** in the root `.env`). |
   | `BAG_ADMIN_USER` | Must equal the **`users.username`** of the account that should access admin (often `admin` after seeding). |
   | `STRIPE_SECRET_KEY`, `STRIPE_PUBLISHABLE_KEY` | Stripe Checkout (use [test keys](https://dashboard.stripe.com/test/apikeys) in development). |

---

## 4. Run migrations (order matters)

1. **Phinx** (schema source of truth for core tables), from the **repo root**:

   ```bash
   vendor\bin\phinx migrate
   ```

   macOS/Linux: `vendor/bin/phinx migrate`

2. **Laravel** migrations (framework tables plus app tables such as `user_search_logs`, `support_requests`, etc.):

   ```bash
   cd laravel
   php artisan migrate
   ```

3. **Optional seed** (dev data):

   ```bash
   vendor\bin\phinx seed:run -s DevDataSeeder
   ```

   Run from the **repo root** (where Phinx lives).

---

## 5. First login (admin)

If you used the dev seeder, an **`admin`** user may exist with a documented password in the seeder (see **`DevDataSeeder`**). Passwords are stored as **salted SHA-256** (`s256$…`). Change the password in production.

Admin UI is restricted by matching **`users.username`** to **`BAG_ADMIN_USER`**.

---

## 6. Storefront AI chat and help desk (optional)

Configure in **`laravel/.env`** (see **`laravel/.env.example`**):

- **Gemini**: `GEMINI_API_KEY`, `GEMINI_MODEL`
- **Free tier + Groq**: `AI_CHAT_TIER=free`, `GROQ_API_KEY`, `GROQ_MODEL`, `AI_CHAT_PRIMARY_WHEN_FREE`, `AI_CHAT_FALLBACK_ENABLED`
- **Paid tier (Gemini only)**: `AI_CHAT_TIER=paid`
- **Rate limits**: `AI_CHAT_THROTTLE_GUEST_PER_MINUTE`, `AI_CHAT_THROTTLE_AUTH_PER_MINUTE`, `AI_SUPPORT_THROTTLE_PER_MINUTE`
- **CAPTCHA** (recommended in production): `AI_CAPTCHA_DRIVER` (`turnstile` or `recaptcha`), plus `TURNSTILE_*` or `RECAPTCHA_*`. If keys are empty, CAPTCHA verification is skipped (convenient for local dev only).

The floating chat is included in the storefront layout; help desk tickets appear under **Help desk** in the admin sidebar.

---

## 7. Run the app locally

**PHP built-in server** (from `laravel/`):

```bash
cd laravel
php artisan serve
```

Open the URL shown (often `http://127.0.0.1:8000`) and ensure **`APP_URL`** matches how you access the site (important for Stripe).

**Static assets**: the project serves files under **`public/assets/`** (symlinked from **`laravel/public/assets`** as described in `README.md`). Ensure that symlink exists on your OS if assets 404.

---

## 8. Docker (optional)

From the repo root:

```bash
docker compose up -d --build
```

Default HTTP entry is often **`http://localhost:8080`**. Set **`LARAVEL_APP_URL`** / **`APP_URL`** so Stripe and redirects match. The app container typically runs Phinx, Composer, and **`php artisan migrate`**; MySQL may be exposed on **`localhost:33060`** (see your `docker-compose` file).

---

## 9. Checklist

- [ ] Root `composer install` and `laravel/composer install` succeeded.
- [ ] Root **`.env`** exists with **`BAG_DB_*`** for Phinx; MySQL database created.
- [ ] **`laravel/.env`** exists with matching **`DB_*`** for Laravel.
- [ ] `php artisan key:generate` run once.
- [ ] `vendor/bin/phinx migrate` (root) then `php artisan migrate` (`laravel/`) completed without errors.
- [ ] `APP_URL` matches the browser URL.
- [ ] Stripe test keys set for checkout flows.
- [ ] `BAG_ADMIN_USER` matches your admin `users.username`.
- [ ] (Optional) AI and CAPTCHA variables set for chat/help desk as needed.

---

## Troubleshooting

- **`php` is not recognized**: Add PHP to your system `PATH`, or run commands inside Docker / WSL / the same environment your team documents.
- **Migration errors**: Run Phinx before Laravel if core tables must exist first; read the error for duplicate tables or missing privileges.
- **419 / CSRF on forms**: Use the same site URL as `APP_URL`, and ensure the session cookie domain is not blocking local hosts.
- **Stripe redirect mismatch**: `APP_URL` and Stripe dashboard allowed URLs must align with how you open the shop.
