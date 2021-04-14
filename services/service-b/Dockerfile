FROM php:7.4-fpm

RUN apt-get update \
    && apt-get install -y librdkafka-dev git zip unzip wget \
    && pecl install rdkafka \
    && docker-php-ext-enable rdkafka # \
#    && curl -s https://getcomposer.org/installer | php \
#    && mv composer.phar /usr/local/bin/composer

CMD ["php-fpm"]

EXPOSE 9000
