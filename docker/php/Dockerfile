# Use the same PHP image as in docker-compose.yml
FROM php:8.1-fpm

# Install necessary dependencies for MySQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql
