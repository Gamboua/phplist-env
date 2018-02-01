#!/bin/bash

set -e

# COPIA DO PHP.INI
cp /var/www/phplist/build/php.ini /etc/php/7.0/fpm/

phpenmod xdebug

hostip=$(ip route show | awk '/default/ {print $3}')

echo "display_errors=on" >> /etc/php/7.0/fpm/php.ini
echo "xdebug.remote_addr_header=$hostip" >> /etc/php/7.0/fpm/conf.d/20-xdebug.ini
echo "xdebug.profiler_enable_trigger=1" >> /etc/php/7.0/fpm/conf.d/20-xdebug.ini
echo "xdebug.remote_connect_back=1" >> /etc/php/7.0/fpm/conf.d/20-xdebug.ini
echo "xdebug.remote_enable=1" >> /etc/php/7.0/fpm/conf.d/20-xdebug.ini
echo "xdebug.profiler_enable_trigger_value=1" >> /etc/php/7.0/fpm/conf.d/20-xdebug.ini
echo "xdebug.remote_connect_back=1" >> /etc/php/7.0/fpm/conf.d/20-xdebug.ini
echo "xdebug.remote_log=/tmp/dbg.log" >> /etc/php/7.0/fpm/conf.d/20-xdebug.ini

echo "fastcgi_param  DEBUG_HOST_IP  $hostip ;" > /etc/nginx/xdebug_param.conf

# AJUSTANDO O VIRTUALHOST
sed -i s/{SERVER_NAME}/$SERVER_NAME/g /etc/nginx/sites-available/vhost.conf

# AJUSTANDO O ARQUIVO DE CONFIGURACAO
cp /var/www/phplist/build/config.php.sample /var/www/phplist/public_html/lists/config/config.php

sed -i s/{DATABASE_HOST}/$DATABASE_HOST/g /var/www/phplist/public_html/lists/config/config.php
sed -i s/{DATABASE_NAME}/$DATABASE_NAME/g /var/www/phplist/public_html/lists/config/config.php
sed -i s/{DATABASE_USER}/$DATABASE_USER/g /var/www/phplist/public_html/lists/config/config.php
sed -i s/{DATABASE_PASSWORD}/$DATABASE_PASSWORD/g /var/www/phplist/public_html/lists/config/config.php
sed -i s/{PHPMAILERHOST}/$PHPMAILERHOST/g /var/www/phplist/public_html/lists/config/config.php

# CONFIG POSTGRESQL
sed -i s/{DATABASE_PGSQL_CAIXA_HOST}/$DATABASE_POSTGRESQL_HOST/g /var/www/phplist/public_html/lists/config/config.php
sed -i s/{DATABASE_PGSQL_CAIXA_NAME}/$POSTGRES_DB/g /var/www/phplist/public_html/lists/config/config.php
sed -i s/{DATABASE_PGSQL_CAIXA_USER}/$POSTGRES_USER/g /var/www/phplist/public_html/lists/config/config.php
sed -i s/{DATABASE_PGSQL_CAIXA_PASSWORD}/$POSTGRES_PASSWORD/g /var/www/phplist/public_html/lists/config/config.php


# AJUSTANDO CONFIG DE MIGRACAO
cp /var/www/phplist/build/phinx.yml.sample /var/www/phplist/phinx.yml

sed -i s/{DATABASE_HOST}/$DATABASE_HOST/g /var/www/phplist/phinx.yml
sed -i s/{DATABASE_NAME}/$DATABASE_NAME/g /var/www/phplist/phinx.yml
sed -i s/{DATABASE_USER}/$DATABASE_USER/g /var/www/phplist/phinx.yml
sed -i s/{DATABASE_PASSWORD}/$DATABASE_PASSWORD/g /var/www/phplist/phinx.yml

# BEHAT CONFIG
# cp /var/www/phplist/default.behat.yml /var/www/phplist/behat.yml
# mysqladmin --no-beep --user=$DATABASE_USER --host=mysql  --password=$DATABASE_PASSWORD create behat_db
# sed -i s/{SERVER_NAME}/$SERVER_NAME/g /var/www/phplist/behat.yml

echo "START FPM"
/etc/init.d/php7.0-fpm start

echo "START NGINX"
nginx -g 'daemon off;'
