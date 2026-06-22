FROM php:7.4

ENV TZ=Asia/Jakarta

# Install system dependencies (including curl for NodeSource)
RUN apt-get update -y && apt-get install -y \
    openssl zip unzip git curl ca-certificates \
    libpq-dev libzip-dev libpng-dev

# Install PHP extensions
RUN docker-php-ext-install bcmath gd zip pdo pdo_pgsql mysqli pdo_mysql exif \
    && docker-php-ext-enable pdo_mysql pdo_pgsql

# Install Node.js 18
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN php -m | grep mbstring

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app
COPY --chown=www-data:www-data . .
RUN composer install
RUN chmod -R 775 storage bootstrap resources bootstrap/cache storage/framework storage/framework/sessions storage

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=5000"]
EXPOSE 5000
