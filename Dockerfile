FROM php:8.2-cli

# Required database extension used by config/database.php
RUN docker-php-ext-install mysqli

WORKDIR /app

# Copy the full app so public pages can include src/ and config/ files.
COPY . .

EXPOSE 8080
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t public public/index.php"]
