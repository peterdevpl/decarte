FROM php:7.3-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
        git \
        libicu-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libsodium-dev \
        zip \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) bcmath gd intl pdo_mysql sodium

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY php-fpm.conf /etc/php-fpm.conf
