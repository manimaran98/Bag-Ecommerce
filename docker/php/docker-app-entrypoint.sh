#!/bin/sh
set -e
cd /var/www/html
if [ ! -f vendor/autoload.php ]; then
  composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader
fi
vendor/bin/phinx migrate

cd /var/www/html/laravel
if [ ! -f vendor/autoload.php ]; then
  composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader --no-scripts
fi
if [ ! -f .env ]; then
  cp .env.example .env
fi
LARAVEL_DB="${LARAVEL_DB_DATABASE:-laravel}"
sed -i.bak 's/^DB_CONNECTION=.*/DB_CONNECTION=mysql/' .env 2>/dev/null || true
if [ -n "${BAG_DB_HOST:-}" ]; then
  sed -i.bak "s/^# DB_HOST=.*/DB_HOST=${BAG_DB_HOST}/" .env 2>/dev/null || true
  sed -i.bak "s/^DB_HOST=.*/DB_HOST=${BAG_DB_HOST}/" .env 2>/dev/null || true
  sed -i.bak "s/^# DB_PORT=.*/DB_PORT=3306/" .env 2>/dev/null || true
  sed -i.bak "s/^DB_PORT=.*/DB_PORT=3306/" .env 2>/dev/null || true
  sed -i.bak "s/^# DB_USERNAME=.*/DB_USERNAME=${BAG_DB_USER:-root}/" .env 2>/dev/null || true
  sed -i.bak "s/^DB_USERNAME=.*/DB_USERNAME=${BAG_DB_USER:-root}/" .env 2>/dev/null || true
  sed -i.bak "s/^# DB_PASSWORD=.*/DB_PASSWORD=${BAG_DB_PASS:-}/" .env 2>/dev/null || true
  sed -i.bak "s/^DB_PASSWORD=.*/DB_PASSWORD=${BAG_DB_PASS:-}/" .env 2>/dev/null || true
fi
sed -i.bak "s/^# DB_DATABASE=.*/DB_DATABASE=${LARAVEL_DB}/" .env 2>/dev/null || true
sed -i.bak "s/^DB_DATABASE=.*/DB_DATABASE=${LARAVEL_DB}/" .env 2>/dev/null || true
sed -i.bak 's/^SESSION_DRIVER=.*/SESSION_DRIVER=file/' .env 2>/dev/null || true
sed -i.bak 's/^QUEUE_CONNECTION=.*/QUEUE_CONNECTION=sync/' .env 2>/dev/null || true
sed -i.bak 's/^CACHE_STORE=.*/CACHE_STORE=file/' .env 2>/dev/null || true
if [ -n "${LARAVEL_APP_URL:-}" ]; then
  sed -i.bak "s|^APP_URL=.*|APP_URL=${LARAVEL_APP_URL}|" .env 2>/dev/null || true
fi
php artisan key:generate --force 2>/dev/null || true
php artisan migrate --force 2>/dev/null || true

cd /var/www/html
exec docker-php-entrypoint "$@"
