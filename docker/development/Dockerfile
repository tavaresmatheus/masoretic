FROM php:8.3-fpm

COPY . /var/www/html

RUN apt-get update \
    && apt-get install -y \
    libpq-dev \
    git \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php

RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

EXPOSE 8080

CMD bash -c "composer install --no-interaction --no-plugins --no-scripts --no-dev --prefer-dist"
