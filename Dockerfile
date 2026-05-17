FROM php:8.3-cli

# Install dependency Linux
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zip \
    curl

# Install ekstensi PHP
RUN docker-php-ext-install pdo pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy project
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate cache
RUN php artisan config:clear
RUN php artisan route:clear
RUN php artisan view:clear

# Expose port
EXPOSE 10000

# Run Laravel
CMD php artisan serve --host=0.0.0.0 --port=10000