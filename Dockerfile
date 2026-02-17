FROM php:8.2-apache

# Install extension CI
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Disable semua MPM lain, aktifkan prefork saja
RUN a2dismod mpm_event || true
RUN a2dismod mpm_worker || true
RUN a2enmod mpm_prefork

# Enable mod_rewrite
RUN a2enmod rewrite

# Set document root ke public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

# Copy project
COPY . /var/www/html/

# Set permission
RUN chown -R www-data:www-data /var/www/html
