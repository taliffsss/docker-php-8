FROM php:8.0.3-apache

# Install pdo_mysql
# install the PHP extensions we need
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip 

RUN apt-get update && \
     apt-get install -y \
         libzip-dev \
         && docker-php-ext-install zip mysqli pdo pdo_mysql mbstring exif pcntl bcmath gd opcache

#Working Directory
WORKDIR /var/www/html

RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini

RUN mkdir /var/www/html/logs && chmod 775 /var/www/html/logs

COPY php.ini /usr/local/etc/php/php.ini
COPY docker-php-ext-opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY school-management.conf /etc/apache2/sites-available/school-management.conf
RUN cd /etc/apache2/sites-available/ && \
    a2ensite school-management.conf && \
    a2dissite 000-default.conf && \
    a2enmod rewrite && a2enmod headers && a2enmod expires && a2enmod ssl && \
    service apache2 restart

EXPOSE 8082