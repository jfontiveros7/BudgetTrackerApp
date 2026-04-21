FROM php:8.2-apache

WORKDIR /var/www/html

COPY public/ .
COPY src/ /var/www/src/

RUN a2enmod rewrite

EXPOSE 80

CMD ["apache2-foreground"]
