FROM debian:stretch

RUN apt-get update && apt-get install -y \
php7.0 \
php7.0-mysqli \
php7.0-pgsql \
php7.0-fpm \
php7.0-mbstring \
php7.0-curl \
php7.0-dom \
php7.0-zip \
php7.0-intl \
php7.0-imap \
nginx \
composer \
pkg-config \
libmemcached-dev \
php7.0-dev \
golang-go \
php-pear \
php-xdebug \
zlib1g-dev \
cron

RUN echo '0 0 * * * root /var/www/phplist/bin/phplist-caixa-import' >> /etc/crontab
RUN echo '0-59/5 * * * * root /var/www/phplist/bin/phplist-caixa-processqueue' >> /etc/crontab

RUN composer global require hirak/prestissimo

COPY composer.json /var/www/phplist/composer.json

RUN composer install -d /var/www/phplist

RUN yes '' | pecl install memcached
RUN echo 'extension=memcached.so' > /etc/php/7.0/mods-available/memcached.ini
RUN phpenmod memcached

RUN phpdismod xdebug

ADD build/vhost.conf /etc/nginx/sites-available/

RUN ln -s /etc/nginx/sites-available/vhost.conf /etc/nginx/sites-enabled

EXPOSE 80

COPY . /var/www/phplist/

WORKDIR  /var/www/phplist

RUN composer install

ENTRYPOINT ./build/init.sh
