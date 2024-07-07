FROM php:apache

RUN apt-get update && apt-get install -y \
  && a2enmod rewrite \
  && docker-php-ext-install mysqli \
  && docker-php-ext-install pdo pdo_mysql \
  && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-interaction --optimize-autoloader

