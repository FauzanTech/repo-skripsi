FROM php:8.2-cli

# Install dependency sistem
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl

# Install ekstensi MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy semua file project
COPY . /app

# Install dependency CI4
RUN composer install --no-dev --optimize-autoloader

# Jalankan built-in PHP server
CMD php -S 0.0.0.0:$PORT -t public
