-- Second database for Laravel (legacy Bag app stays on MYSQL_DATABASE e.g. bag_biz).
CREATE DATABASE IF NOT EXISTS laravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON laravel.* TO 'bag'@'%';
FLUSH PRIVILEGES;
