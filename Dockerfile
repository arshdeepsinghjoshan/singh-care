# Use the official PHP image with Apache
FROM php:8.1.8-apache

# Update package list and install necessary packages
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    curl \
    sudo \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    libzip-dev \
    g++

# Enable Apache modules
RUN a2enmod rewrite headers

# Install necessary PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
ADD . /var/www/html

# Fix Git ownership error
RUN git config --global --add safe.directory /var/www/html

# Install PHP dependencies using Composer
RUN composer install --no-dev --optimize-autoloader

# Set permissions for Laravel storage and cache directories
RUN chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 8000 for Laravel server
EXPOSE 8000

# Run both Laravel server and scheduler
CMD bash -c "php artisan serve"
