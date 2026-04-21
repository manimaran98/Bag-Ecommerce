# Bag Ecommerce (PHP)

University-style bag shop: customer storefront, shopping cart, bank-transfer checkout with receipt upload, and an admin area for users, inventory, suppliers, orders, and purchase validation.

## Architecture: backend vs frontend

This repo is organized like a small **backend + frontend** split (without a separate JS build step):

| Layer | Path | Role |
|--------|------|------|
| **Backend** | `backend/` | Not a public API: PHP that boots the app, connects to MySQL, enforces auth, and runs all **request handlers** (forms, cart, checkout, admin actions). |
| **Frontend** | `frontend/` | **Views only**: `frontend/views/` templates and `frontend/View.php` (`bag_view()`). HTML presentation is separated from `backend/Http/handlers.php`. |
| **Public assets** | `assets/` | Static CSS/images/uploads at the document root (URLs stay `assets/...`). |
| **Entry scripts** | `*.php` at repo root | Thin “controllers”: `require server.php` (backend), then `bag_view(...)` for pages that use templates. Legacy admin/shop pages still inline HTML; migrate them to `frontend/views/pages/` over time. |

- **`server.php`** → loads **`backend/bootstrap.php`** (env, session, DB, auth guard, then **`backend/Http/handlers.php`**).
- **`backend/.htaccess`** (Apache) denies direct browser access to the backend folder; PHP still loads it via `require`.

### Example flow

1. Browser requests `index.php?logout=1` or POST to `login.php`.
2. **`server.php`** runs the backend: handlers may redirect, update DB, set flash errors, etc.
3. **`index.php`** / **`login.php`** then call **`bag_view('pages/...')`** to render HTML only.

## Project layout (paths)

| Path | Purpose |
|------|---------|
| `backend/bootstrap.php` | Session, DB, load Support + route guard + handlers |
| `backend/Config/database.php` | DB settings (`BAG_DB_*` / `.env`) |
| `backend/Support/` | `auth.php`, `helpers.php` |
| `backend/Http/handlers.php` | All POST/GET action logic (the former monolithic `server.php` body) |
| `frontend/View.php` | `bag_view($path, $data)` |
| `frontend/views/pages/` | Page templates (`home`, `login`, `register`, …) |
| `frontend/views/partials/` | Reusable fragments (e.g. customer nav) |
| `assets/` | Public static files + upload dirs |
| `database/bag_biz.sql` | Schema and sample data |

PHP entry scripts live in the **project root**, which should be the web server document root (e.g. XAMPP `htdocs/Bag-Ecommerce`).

## Requirements

- PHP **7.4+** (8.x recommended) with `mysqli`, `fileinfo` (for safer downloads), sessions enabled  
- MySQL or MariaDB  

## Installation

1. **Create the database** and import the schema:
   ```bash
   mysql -u root -p -e "CREATE DATABASE bag_biz CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   mysql -u root -p bag_biz < database/bag_biz.sql
   ```
2. **Existing databases** created before this repo update: widen the password column so bcrypt hashes fit:
   ```sql
   ALTER TABLE users MODIFY password VARCHAR(255) NOT NULL;
   ```
3. **Configuration**: copy `.env.example` to `.env` and set database credentials (or export `BAG_DB_HOST`, `BAG_DB_USER`, `BAG_DB_PASS`, `BAG_DB_NAME`). Defaults match local XAMPP (`root` / empty password / `bag_biz`).
4. **Admin account**: default seed user is `admin` with password **`12345`** (MD5 in old dumps). Log in once; the app will **re-hash** the password to bcrypt automatically. Change the password immediately in production.
5. **Admin username**: optional env `BAG_ADMIN_USER` (default `admin`) must match the `users.username` row that should receive the admin dashboard after login.

## Security improvements (this fork)

- **Passwords**: `password_hash` / `password_verify`; legacy **MD5** in the database is verified once and upgraded to bcrypt on successful login.
- **Authorization**: Admin-only scripts (dashboard, CRUD, deletes) require an authenticated **admin** user. Customer accounts receive HTTP 403 if they open admin URLs.
- **SQL injection**: High-risk updates and several deletes use **prepared statements** or typed IDs; remaining dynamic SQL uses escaped strings where applicable.
- **Uploads**: Product images and payment receipts are stored with **random filenames** and **allowed extensions** (images: jpg/png/gif/webp; receipts also **pdf**).
- **Downloads**: `download.php` resolves files under `assets/receipt/` only and allows **admin** or the **owning customer** (matching purchase row).
- **Sessions**: HTTP-only session cookies, `SameSite=Lax`.
- **Receipts**: `assets/receipt/*` is gitignored except `.gitkeep`; do not commit real customer uploads.

## Development notes

- Login URL is **`login.php`** (lowercase) for compatibility with Linux hosting.
- After changing code, clear browser cookies if session behavior seems stuck.
- To add a new page: create `frontend/views/pages/yourpage.php`, add a root `yourpage.php` that `require`s `server.php` + `frontend/View.php`, then `bag_view('pages/yourpage', $data)`.

## License

Educational / project use; adapt as needed for your course or deployment.
