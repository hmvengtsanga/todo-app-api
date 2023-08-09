# Use an official PHP runtime as a base image
FROM php:8.1-apache


RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
  \
     && apt-get update && \
        apt-get install --yes --force-yes \
        cron g++ gettext libicu-dev openssl \
        libc-client-dev libkrb5-dev  \
        libxml2-dev libfreetype6-dev \
        libgd-dev libmcrypt-dev bzip2 \
        libbz2-dev libtidy-dev libcurl4-openssl-dev \
        libz-dev libmemcached-dev libxslt-dev git-core libpq-dev \
        libzip4 libzip-dev libwebp-dev

# PHP Configuration
RUN docker-php-ext-install bcmath bz2 calendar  dba exif gettext iconv intl  soap tidy xsl zip&&\
    docker-php-ext-install mysqli pgsql pdo pdo_mysql pdo_pgsql  &&\
    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp &&\
    docker-php-ext-install gd &&\
    docker-php-ext-configure imap --with-kerberos --with-imap-ssl &&\
    docker-php-ext-install imap &&\
    docker-php-ext-configure hash --with-mhash &&\
    pecl install xdebug && docker-php-ext-enable xdebug &&\
    pecl install mongodb && docker-php-ext-enable mongodb &&\
    pecl install redis && docker-php-ext-enable redis && \
    curl -sS https://getcomposer.org/installer | php \
            && mv composer.phar /usr/bin/compose

# Apache Configuration
RUN a2enmod rewrite 


# RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
# \
#     &&  apt-get update \
#     &&  apt-get install -y --no-install-recommends \
#         locales apt-utils git libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev unzip \
# \
#     &&  echo "en_US.UTF-8 UTF-8" > /etc/locale.gen  \
#     &&  echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen \
#     &&  locale-gen \
# \
#     &&  curl -sS https://getcomposer.org/installer | php -- \
#     &&  mv composer.phar /usr/local/bin/composer \
# # \
# #     &&  curl -sS https://get.symfony.com/cli/installer | bash \
# #     &&  mv /root/.symfony/bin/symfony /usr/local/bin \
# \ 
#     &&  docker-php-ext-configure \
#             intl \
#     &&  docker-php-ext-install \
#             pdo pdo_mysql opcache intl zip calendar dom mbstring gd xsl \
# \
#     &&  pecl install apcu && docker-php-ext-enable apcu

WORKDIR /var/www/
