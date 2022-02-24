FROM php:7.4-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
        git \
        htop \
        libicu-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libsodium-dev \
        vim \
        zip \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) bcmath gd intl pdo_mysql sodium \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY php-fpm.conf /etc/php-fpm.conf
COPY xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN useradd -ms /bin/bash docker
USER docker

ENTRYPOINT ["/bin/bash", "/var/www/docker/php-fpm/entrypoint.sh"]
