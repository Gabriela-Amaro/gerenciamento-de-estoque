FROM php:8.2-apache

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN apt-get update \
    && apt-get install -qq -y --no-install-recommends \
    wget locales coreutils apt-utils git libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev;

RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
   mv composer.phar /usr/local/bin/composer

RUN docker-php-ext-install pdo pdo_mysql mysqli opcache intl calendar dom mbstring zip gd xsl && a2enmod rewrite
RUN docker-php-ext-configure intl

RUN wget https://get.symfony.com/cli/installer -O - | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

COPY ./stock_man /var/www/stock_man/

RUN chmod -R 777 /var/www

RUN git config --global --add safe.directory /var/www

RUN chown -R www-data:www-data /var/www/stock_man

WORKDIR /var/www/stock_man

RUN composer require symfony/orm-pack \
    && composer require --dev symfony/maker-bundle \
    && composer install --no-interaction --optimize-autoloader --prefer-dist --no-cache
