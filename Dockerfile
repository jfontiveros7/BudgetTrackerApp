FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader \
    --ignore-platform-req=ext-mysqli

FROM php:8.2-cli

WORKDIR /app

RUN docker-php-ext-install mysqli

COPY --from=vendor /app/vendor ./vendor
COPY . .

RUN chmod +x start.sh

ENV PORT=8080

EXPOSE 8080

CMD ["bash", "./start.sh"]
