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
COPY public/ ./public/
COPY src/ /var/www/src/

ENV PORT=80

RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

RUN a2dismod mpm_prefork mpm_event mpm_worker 2>/dev/null || true
RUN a2enmod mpm_prefork
RUN a2enmod rewrite

EXPOSE 80

CMD ["apache2-foreground"]
