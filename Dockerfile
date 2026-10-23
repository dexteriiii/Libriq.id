# Stage 1: Build Assets Frontend (Vite & Tailwind)
FROM node:20-alpine AS frontend_builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: PHP Application Environment
FROM php:8.2-apache

# Aktifkan mod_rewrite Apache untuk Laravel
RUN a2enmod rewrite

# Install ekstensi yang dibutuhkan Laravel, MySQL, dan PostgreSQL
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev zip unzip git libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip gd \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set directory dan copy project
WORKDIR /var/www/html
COPY . /var/www/html

# Salin aset yang sudah dibuild dari frontend_builder
COPY --from=frontend_builder /app/public/build /var/www/html/public/build

# Arahkan Apache DocumentRoot ke folder /public dan atur izin folder
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Install dependencies composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Entrypoint script untuk auto migrate dan cache
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
