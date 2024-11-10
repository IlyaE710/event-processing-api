FROM php:8.3-cli-alpine

RUN apk upgrade --no-cache \
    && apk add --no-cache linux-headers $PHPIZE_DEPS \
    && docker-php-ext-install sockets pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

