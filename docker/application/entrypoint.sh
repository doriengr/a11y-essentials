#!/bin/bash

mkdir -p storage/framework/{sessions,views,cache}
mkdir content
mkdir -p public/assets
mkdir users
php artisan statamic:install
php artisan optimize:clear
php artisan app:init-globals
php please stache:warm
# The cache is warmed in the `warm_static_cache` service in the `docker-compose.main.yml` and `docker-compose.dev.yml` files.
# If it would be done here, the website might not be ready for requests yet.
php please static:clear
php artisan optimize
php -r 'opcache_reset();'

chown -R vvuser:vvuser /var/www/html/storage
chown -R vvuser:vvuser /var/www/html/content
chown -R vvuser:vvuser /var/www/html/public/assets
chown -R vvuser:vvuser /var/www/html/users

exec "$@"
