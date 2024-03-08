FROM php:8.1-fpm



RUN apt update \
    && apt -y upgrade \
    && apt install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libtidy-dev \
        libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mysqli pdo_mysql exif tidy zip

RUN apt-get update && apt-get install -y composer


COPY . /var/www/html

WORKDIR /var/www/html

RUN composer install

EXPOSE 80

CMD ["php", "artisan", "serve"]

FROM php:8.0-apache

