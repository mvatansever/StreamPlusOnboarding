# Use the same PHP image as in docker-compose.yml
FROM php:8.1-fpm

# Install necessary dependencies for MySQL and other required tools
RUN apt-get update && apt-get install -y \
    libpq-dev \
    curl \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory
WORKDIR /var/www/html
