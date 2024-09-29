
FROM php:8.0-apache


WORKDIR /var/www/html


RUN docker-php-ext-install pdo pdo_mysql mysqli


COPY ./src/ /var/www/html/
COPY ./src/config/php.ini /usr/local/etc/php/

EXPOSE 80
