FROM php:8.1.23-apache 
RUN docker-php-ext-install pdo_mysql
RUN a2enmod rewrite