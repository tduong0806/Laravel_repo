FROM php:8.3-fpm

# Arguments defined in docker-compose.yml
# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    wget \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libc-client-dev \
    libkrb5-dev \
    npm \
    vim \
    libzip-dev

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip imap

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# COPY . /var/www/html
# RUN composer self-update --1

# RUN COMPOSER_MEMORY_LIMIT=-1 composer install --ignore-platform-reqs

# Set working directory
WORKDIR /var/www/html

EXPOSE 82/tcp