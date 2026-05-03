FROM php:8.2-cli

WORKDIR /app

# Copy source code
COPY . .

# Cài extension cần thiết
RUN apt-get update && apt-get install -y \
    unzip curl libzip-dev zip \
    && docker-php-ext-install zip pdo pdo_mysql

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Cài dependency Laravel
RUN composer install

# Mở port
EXPOSE 8000

# Chạy Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000