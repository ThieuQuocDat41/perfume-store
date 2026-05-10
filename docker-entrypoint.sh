#!/bin/sh
set -e

echo "Waiting DB..."

while ! nc -z db 3306; do
  sleep 2
done

echo "DB ready!"

php artisan storage:link || true
chmod -R 775 storage bootstrap/cache || true

php artisan migrate --force || true

# 🔥 QUAN TRỌNG: dùng PHP built-in server đúng public/
exec php -S 0.0.0.0:8000 -t public
