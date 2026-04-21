# PHP-FPM: Laravel + Phinx migrations (repo root)
FROM php:8.2-fpm-bookworm

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libicu-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        mysqli \
        pdo_mysql \
        gd \
        zip \
        intl \
        opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Safer defaults for production-like containers
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY docker/php/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /var/www/html

COPY composer.json ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

COPY . .

RUN cd laravel && composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

RUN mkdir -p laravel/storage/framework/sessions laravel/storage/framework/views \
    laravel/storage/framework/cache/data laravel/storage/logs laravel/bootstrap/cache \
    && chown -R www-data:www-data laravel/storage laravel/bootstrap/cache

# Share storefront static assets (images, CSS) with Laravel public URL
RUN ln -sf /var/www/html/public/assets /var/www/html/laravel/public/assets

RUN chmod +x docker/php/docker-app-entrypoint.sh

ENTRYPOINT ["/var/www/html/docker/php/docker-app-entrypoint.sh"]
CMD ["php-fpm"]
