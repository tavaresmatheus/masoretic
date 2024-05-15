FROM php:8.3-fpm-alpine

COPY . /var/www/html

RUN apk --no-cache update \
    && apk add \
    git \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php

RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

RUN composer install

EXPOSE 8080
