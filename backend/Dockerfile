# Build stage
FROM composer:latest AS composer

WORKDIR /app

# Copy only composer files
COPY composer.* ./

# Install dependencies
RUN composer install --no-scripts --no-autoloader --no-dev

# Production stage
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    && docker-php-ext-install zip pdo_mysql

WORKDIR /var/www

# Copy application files first
COPY . .

# Then copy vendor directory
COPY --from=composer /app/vendor ./vendor
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Set up storage and cache directories
RUN mkdir -p /var/www/storage/framework/{sessions,views/cache,views/compiled} \
    && mkdir -p /var/www/storage/logs \
    && chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

# Generate optimized autoloader and run post-install scripts
RUN composer dump-autoload --optimize \
    && php artisan key:generate --force \
    && php artisan config:cache \
    && php artisan route:cache

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]