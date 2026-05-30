# Use PHP 8.4 FPM as the base image
FROM php:8.4-fpm

# Install system dependencies required by PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libzip-dev \
    libxml2-dev \
    libonig-dev \
    zip \
    unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required by Laravel
RUN docker-php-ext-install pdo_mysql mbstring xml zip bcmath

# Install Redis PHP extension via PECL
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy only composer files
COPY composer.json composer.lock ./ 

# Install Laravel dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader --no-scripts

# Copy application code
COPY . .

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]