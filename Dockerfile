FROM php:8.2-apache

# Install PHP extensions
RUN docker-php-ext-install mysqli

WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files and install dependencies
COPY composer.json ./
COPY composer.lock* ./
RUN composer install --no-dev --optimize-autoloader

# Copy application files
COPY public/ .
COPY src/ /var/www/src/

RUN a2enmod rewrite

EXPOSE 80

CMD ["apache2-foreground"]
