FROM php:8.2-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/app/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN apt-get update \
&&  apt-get install -y --no-install-recommends \
locales apt-utils git libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev unzip

RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && \
locale-gen

RUN curl -sS https://getcomposer.org/installer | php -- \
&&  mv composer.phar /usr/local/bin/composer

RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_mysql mysqli gd opcache intl zip calendar dom mbstring zip gd xsl && a2enmod rewrite
RUN pecl install apcu && docker-php-ext-enable apcu
    
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions amqp
     
WORKDIR /var/www/app
CMD ["apache2-foreground"]