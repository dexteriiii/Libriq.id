FROM php:8.2-apache

# Aktifkan mod_rewrite Apache untuk Laravel
RUN a2enmod rewrite

# Install ekstensi yang dibutuhkan Laravel & MySQL
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev zip unzip git libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set directory dan copy project
WORKDIR /var/www/html
COPY . /var/www/html

# Arahkan Apache DocumentRoot ke folder /public dan atur izin folder
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Install dependencies project
RUN composer install --no-dev --optimize-autoloader

EXPOSE 80