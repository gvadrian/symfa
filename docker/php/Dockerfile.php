FROM php:8.0.1-fpm-alpine
ARG ARCH="amd64"
ARG OS="linux"
ARG OPCACHE=1
LABEL maintainer="Yaroslav Hryshchenko <bash.test.sh@gmail.com>"

RUN apk add --no-cache zlib-dev libzip-dev libpng-dev freetype-dev libjpeg-turbo-dev gcc make g++ php7-dev icu-dev librdkafka-dev


RUN pecl install -o -f redis && \
    pecl install -o -f  rdkafka-5.0.0

RUN docker-php-ext-install pdo_mysql && \
    docker-php-ext-configure gd \
      --with-freetype \
      --with-jpeg && \
    docker-php-ext-install gd && \
    docker-php-ext-configure opcache \
      --enable-opcache && \
    if [ "$OPCACHE" == 1 ]; then \
    docker-php-ext-install opcache; \
    fi && \
    docker-php-ext-install zip && \
    docker-php-ext-enable redis && \
    docker-php-ext-configure intl && \
    docker-php-ext-install intl && \
    docker-php-ext-enable rdkafka

USER www-data

COPY  --chown=www-data:www-data . /var/www/html/public
WORKDIR /var/www/html/
