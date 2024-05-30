FROM php:apache

RUN apt-get update && apt-get install -y \
  && a2enmod rewrite \
  && docker-php-ext-install mysqli \
  && docker-php-ext-install pdo pdo_mysql