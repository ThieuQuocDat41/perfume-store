#!/bin/sh
set -e

<<<<<<< HEAD
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
=======
# storage link
if [ ! -L public/storage ]; then
  php artisan storage:link || true
fi

# permissions
chmod -R 775 storage bootstrap/cache || true

# generate key nếu chưa có
php artisan key:generate --force || true

# migrate database
php artisan migrate --force || true

# start server
exec php artisan serve --host=0.0.0.0 --port=8000
>>>>>>> 3aad823 (update)
