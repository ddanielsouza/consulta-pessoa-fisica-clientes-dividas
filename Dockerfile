FROM php:7.2-apache
COPY . /app
WORKDIR /app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apt-get update -y  && apt-get install libpq-dev git gzip zip unzip -y \
    && docker-php-ext-install pgsql \
    && docker-php-ext-install pdo_pgsql \
    && composer install \
    && rm -rf /var/www/html \
    && ln -s /app/public /var/www/html \
    && chgrp -R www-data /app \
    && chmod -R 775 /app/storage

RUN a2enmod rewrite
EXPOSE 80
