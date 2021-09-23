FROM php:8.0.8-apache

# Set up the Docker Working Directory 
ENV HOME=/var/www/html/api/
WORKDIR $HOME

# Update Server Date time
ENV TZ=Asia/Manila
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Install pdo_mysql
# install the PHP extensions we need
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip mysqli pdo pdo_pgsql pgsql pdo_mysql mbstring exif pcntl bcmath gd opcache intl

RUN curl -sS https://getcomposer.org/installer | php \
    && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer

RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

# Change default docker container ownership to www-data
RUN usermod -u 1000 www-data
RUN usermod -G staff www-data

# Stdout logs
RUN ln -sf /dev/stdout /var/log/apache2/api_error.log
RUN chown -R $USER:www-data /var/log/apache2/api_error.log

# Setup apache
COPY api.conf /etc/apache2/sites-available/
COPY opcache.ini /usr/local/etc/php/conf.d/

# Enable Apache module
RUN a2enmod rewrite headers expires deflate ssl proxy lbmethod_byrequests proxy_balancer proxy_http

RUN cd /etc/apache2/sites-available/ && \
    a2ensite api.conf && \
    a2dissite 000-default.conf && \
    service apache2 restart

# Move all files to $HOME
ADD . $HOME