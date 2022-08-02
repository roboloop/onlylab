FROM php:7.4-fpm

RUN apt update && apt install -y \
    vim sqlite3

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
RUN install-php-extensions zip xdebug

COPY docker/fpm/symfony_pool.conf /usr/local/etc/php-fpm.d/symfony_pool.conf
COPY docker/fpm/symfony.ini docker/fpm/xdebug.ini /usr/local/etc/php/conf.d/

WORKDIR /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer --version

RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs
