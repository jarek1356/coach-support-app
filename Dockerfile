# Używamy standardowego obrazu PHP z Alpine
FROM php:8.2-fpm-alpine

# Instalacja niezbędnych rozszerzeń dla Symfony i PostgreSQL
RUN apk add --no-cache git postgresql-dev icu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_pgsql intl

# Instalacja Composera globalnie w kontenerze
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

CMD ["php-fpm"]