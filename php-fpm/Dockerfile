FROM php:7.4-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
   git \
   libzip-dev \
   zip \
   unzip
RUN docker-php-ext-configure zip
RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

RUN curl --silent --show-error https://getcomposer.org/installer | php && \
   mv composer.phar /usr/local/bin/composer

EXPOSE 9000
CMD [ "php-fpm" ]