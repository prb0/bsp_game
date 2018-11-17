FROM php:7.2-apache

RUN docker-php-ext-install \
	pdo_mysql \
	&& a2enmod \
	rewrite

COPY ./src /var/www