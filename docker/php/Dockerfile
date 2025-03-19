FROM php:8.2-fpm

RUN apt-get update && apt-get install -y git
RUN docker-php-ext-install pdo_mysql

RUN apt-get install -y \
        libzip-dev \
        zip \
        zlib1g-dev \
        libpng-dev \
        libjpeg-dev \
        libwebp-dev \
  && docker-php-ext-install zip opcache

RUN docker-php-ext-configure gd --with-jpeg --with-webp && \
    docker-php-ext-install gd opcache

RUN pecl install APCu && \
    docker-php-ext-enable apcu

RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

COPY --from=composer:2.1 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
