FROM php:8.3-fpm

# Sistem paketleri
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install \
        intl \
        zip \
        pdo \
        pdo_mysql

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-interaction --optimize-autoloader

# Laravel'i Railway'in verdiği portta çalıştır
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"]
