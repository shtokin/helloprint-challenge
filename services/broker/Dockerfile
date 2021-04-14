FROM php:7.4-fpm

RUN apt-get update \
    && apt-get install -y librdkafka-dev git zip unzip wget \
    && apt-get install -y libpq-dev \
    && pecl install rdkafka \
    && docker-php-ext-enable rdkafka \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pgsql pdo pdo_pgsql # \
    #&& curl -s https://getcomposer.org/installer | php \
    #&& mv composer.phar /usr/local/bin/composer

WORKDIR /var/www/html

COPY --chown=www-data:www-data . /var/www/html

USER www-data

CMD ["php-fpm"]

EXPOSE 9000