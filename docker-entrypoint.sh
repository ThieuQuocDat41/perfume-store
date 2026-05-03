dir /a
#!/bin/sh
set -e

# Ensure storage symlink exists
if [ ! -L public/storage ]; then
  php artisan storage:link || true
fi

# Ensure permissions (best-effort)
if id www-data >/dev/null 2>&1; then
  chown -R www-data:www-data storage bootstrap/cache || true
fi

# Start Laravel built-in server
exec php artisan serve --host=0.0.0.0 --port=8000
