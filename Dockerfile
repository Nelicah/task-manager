FROM php:8.2-apache

RUN a2enmod rewrite headers

RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

RUN docker-php-ext-install pdo pdo_mysql

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
