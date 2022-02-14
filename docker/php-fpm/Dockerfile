FROM php:7.4-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
        git \
        libicu-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libsodium-dev \
        zip \
    && docker-php-ext-install -j$(nproc) bcmath gd intl pdo_mysql sodium

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY php-fpm.conf /etc/php-fpm.conf

RUN useradd -ms /bin/bash docker
USER docker

ENTRYPOINT ["/bin/bash", "/var/www/docker/php-fpm/entrypoint.sh"]
