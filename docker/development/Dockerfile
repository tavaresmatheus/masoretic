FROM php:8.3-fpm-alpine

COPY . /var/www/html

RUN apk --no-cache update \
    && apk add \
    git \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql

ENV COMPOSER_HOME /composer

ENV PATH ./vendor/bin:/composer/vendor/bin:$PATH

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php

RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

RUN composer install

EXPOSE 8080
