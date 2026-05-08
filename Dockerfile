FROM php:8.2-apache

RUN a2enmod rewrite headers

RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

RUN docker-php-ext-install pdo pdo_mysql

RUN echo 'PassEnv DB_HOST DB_NAME DB_USER DB_PASS DB_PORT' > /etc/apache2/conf-available/passenv.conf \
    && a2enconf passenv

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
