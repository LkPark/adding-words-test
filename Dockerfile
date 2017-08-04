FROM php:7.0-apache

ADD 000-default.conf /etc/apache2/sites-enabled/000-default.conf

COPY ./ /var/www/html/

RUN a2enmod rewrite