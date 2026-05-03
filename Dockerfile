FROM node:20-alpine AS node_builder
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm ci --silent
COPY . .
RUN npm run build

FROM php:8.2-cli
WORKDIR /app

# System deps
RUN apt-get update && apt-get install -y \
    unzip curl libzip-dev zip git \
    && docker-php-ext-install zip pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application source
COPY . .

# Copy built frontend assets from node builder
COPY --from=node_builder /app/public/build /app/public/build

# Install PHP deps
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Entrypoint takes care of storage link and permissions
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 8000
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
