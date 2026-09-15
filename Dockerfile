FROM php:8.2-cli

# Install extension pendukung (PostgreSQL & Zip)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Install dependency Laravel
RUN composer install --no-dev --optimize-autoloader

# Expose port yang digunakan Render
EXPOSE 10000

# Perintah untuk menjalankan Laravel
CMD php artisan config:cache && php artisan route:cache && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
