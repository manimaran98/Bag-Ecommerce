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
REDIS_HOST_VAL="${REDIS_HOST:-redis}"
sed -i.bak "s/^REDIS_HOST=.*/REDIS_HOST=${REDIS_HOST_VAL}/" .env 2>/dev/null || true
sed -i.bak 's/^REDIS_CLIENT=.*/REDIS_CLIENT=phpredis/' .env 2>/dev/null || true
sed -i.bak 's/^SESSION_DRIVER=.*/SESSION_DRIVER=redis/' .env 2>/dev/null || true
sed -i.bak 's/^QUEUE_CONNECTION=.*/QUEUE_CONNECTION=redis/' .env 2>/dev/null || true
sed -i.bak 's/^CACHE_STORE=.*/CACHE_STORE=redis/' .env 2>/dev/null || true
if [ -n "${LARAVEL_APP_URL:-}" ]; then
  sed -i.bak "s|^APP_URL=.*|APP_URL=${LARAVEL_APP_URL}|" .env 2>/dev/null || true
fi
php artisan key:generate --force 2>/dev/null || true
php artisan migrate --force 2>/dev/null || true

cd /var/www/html
exec docker-php-entrypoint "$@"
