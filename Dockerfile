FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    libzip-dev \
    libpq-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libmcrypt-dev \
    libicu-dev \
    g++ \
    git \
    libmagickwand-dev \
    libxslt-dev \
    default-mysql-client \
    npm \
    && docker-php-ext-install pdo pdo_mysql

# Set working directory
WORKDIR /var/www/html