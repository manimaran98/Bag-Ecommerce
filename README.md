# HalenMiaga (Laravel)

**HalenMiaga** is a bag shop with a customer storefront (products, cart, **Stripe Checkout**), order history, invoices, **personalized recommendations** (purchase co-occurrence + logged category filters + popularity fallback), and an **admin** area (users, stock, suppliers, deliveries, purchase validation, sales report).

The application is the **`laravel/`** Laravel 11 app. The MySQL schema is managed with **Phinx** at the repo root (`database/migrations/`); Laravel uses the same database (typically **`bag_biz`**) for Eloquent. Static assets and uploads live under **`public/assets/`** and are exposed via a symlink from **`laravel/public/assets`**.

## Requirements

- PHP **8.2+** (Docker image includes extensions for MySQL, GD, etc.)
- Composer
- MySQL 8

## Local setup

1. **Root:** `composer install` (Phinx only).
2. **Laravel:** `cd laravel && composer install`.
3. Create database `bag_biz` (utf8mb4), copy **`laravel/.env.example`** to **`laravel/.env`**, set **`DB_*`**, **`APP_URL`**, **`BAG_ADMIN_USER`** (must match `users.username` for the admin account), and **`STRIPE_SECRET_KEY`** (test key for Stripe Checkout).
4. Run Phinx: `vendor/bin/phinx migrate` from the repo root.
5. Optional seed: `vendor/bin/phinx seed:run -s DevDataSeeder`.
6. Laravel framework tables + app tables (e.g. `user_search_logs` for recommendations): `cd laravel && php artisan migrate`.

Optional: **`BAG_RECOMMENDATION_LIMIT`** (default `8`) in **`laravel/.env`** controls how many suggested products appear on home, product list, and product detail.

### Storefront AI chat and help desk

- **Floating chat** on the storefront: **`POST /chat`** (rate-limited + CAPTCHA when configured). Set **`GEMINI_API_KEY`** (and for free tier **`GROQ_API_KEY`**) and related vars in **`laravel/.env`** — see **`laravel/.env.example`**. With **`AI_CHAT_TIER=free`**, the app uses **`AI_CHAT_PRIMARY_WHEN_FREE`** (`groq` or `gemini`) and can fall back to the other provider on errors when **`AI_CHAT_FALLBACK_ENABLED=true`**. With **`AI_CHAT_TIER=paid`**, only Gemini is used.
- **Help desk tickets**: customers can escalate from the chat widget (**`POST /support-requests`**, stricter throttle + CAPTCHA). Admins open **Help desk** in the sidebar to list and resolve tickets.
- **CAPTCHA**: configure **Cloudflare Turnstile** (`TURNSTILE_*`) or **reCAPTCHA v3** (`RECAPTCHA_*`) and **`AI_CAPTCHA_DRIVER`**. Without site/secret keys, verification is skipped (development only).

**Admin:** after seeding, user **`admin`** / **`12345`** (MD5 in seed) — log in once to re-hash to bcrypt; change the password in production.

## Docker

Single HTTP entry point: **nginx** → **`laravel/public`** (default **http://localhost:8080**).

```bash
docker compose up -d --build
```

The **app** container runs Phinx, installs Composer deps, configures **`laravel/.env`** from environment variables, runs **`php artisan migrate`**, then **php-fpm**. Set **`LARAVEL_APP_URL`** (e.g. `http://localhost:8080`) so **`APP_URL`** and Stripe redirect URLs match.

MySQL is exposed on **localhost:33060** by default. RabbitMQ is optional for future workers.

## Project layout

| Path | Role |
|------|------|
| `laravel/` | Application (routes, controllers, Blade, services). |
| `public/assets/` | CSS, images; `stockImg/` and `receipt/` uploads (gitignored where appropriate). |
| `database/migrations/` | Phinx schema (source of truth for tables). |
| `phinx.php` | Phinx config (`BAG_DB_*`). |

## Security notes

- Passwords: bcrypt; legacy **MD5** in the database is upgraded on successful login.
- Admin routes use middleware + `users.username === BAG_ADMIN_USER`.
- Receipt download: admin or owning customer (`GET /download/receipt?payment_resit=...`).
- Uploads: random filenames; allowed types for product images and receipts (images + PDF for receipts).

## License

Educational / project use.
