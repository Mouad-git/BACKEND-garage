FROM php:8.2-cli

WORKDIR /app

# Installer extensions nécessaires ;;
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier le projet
COPY . .

# Installer dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Exposer le port Railway
EXPOSE 8080

CMD php artisan serve --host=0.0.0.0 --port=8080