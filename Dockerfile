FROM php:8.2-fpm

# Installa dipendenze di sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Pulisce cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Installa estensioni PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installa Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Imposta directory di lavoro
WORKDIR /var/www

# Copia file applicazione
COPY . /var/www

# Installa dipendenze PHP
RUN composer install --optimize-autoloader --no-dev

# Installa dipendenze Node.js
RUN npm install && npm run build

# Imposta permessi
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Espone porta 9000
EXPOSE 9000

CMD ["php-fpm"]