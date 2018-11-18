FROM php:7.2-apache

RUN docker-php-ext-install \
	pdo_mysql \
	&& a2enmod \
	rewrite \
	&& sed -i "s%/var/www/html%/var/www/html/public%g" "/etc/apache2/sites-enabled/000-default.conf" \
	&& service apache2 restart

COPY ./src /var/www