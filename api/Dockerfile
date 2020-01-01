FROM php:7.3.12-fpm

RUN apt-get -y update && \
    apt-get -y install git unzip libicu-dev

RUN curl -sL 'https://raw.githubusercontent.com/composer/getcomposer.org/master/web/installer' | php -- --quiet && \
    mv composer.phar /usr/bin/composer && \
    pecl install redis && \
    echo 'extension=redis.so' > /usr/local/etc/php/conf.d/redis.ini && \
    pecl install xdebug-2.8.0 && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-install intl
