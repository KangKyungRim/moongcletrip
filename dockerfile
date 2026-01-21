FROM php:7.4-apache

# Apache rewrite
RUN a2enmod rewrite

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

RUN apt-get update \
    && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd \
    && docker-php-ext-install gd

RUN apt-get update \
    && apt-get install -y libzip-dev zip unzip \
    && docker-php-ext-install zip

RUN mkdir -p /var/www/html/sessions \
    && chmod -R 777 /var/www/html/sessions

# Apache DocumentRoot -> /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock /var/www/html/
RUN composer install --no-dev --optimize-autoloader

COPY . /var/www/html