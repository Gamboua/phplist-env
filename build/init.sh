#!/bin/bash

set -e

# AJUSTANDO O VIRTUALHOST
sed -i s/{SERVER_NAME}/$SERVER_NAME/g /etc/nginx/sites-available/vhost.conf

# AJUSTANDO O ARQUIVO DE CONFIGURACAO
cp /var/www/phplist/build/config.php.sample /var/www/phplist/public_html/lists/config/config.php

sed -i s/{DATABASE_HOST}/$DATABASE_HOST/g /var/www/phplist/public_html/lists/config/config.php
sed -i s/{DATABASE_NAME}/$DATABASE_NAME/g /var/www/phplist/public_html/lists/config/config.php
sed -i s/{DATABASE_USER}/$DATABASE_USER/g /var/www/phplist/public_html/lists/config/config.php
sed -i s/{DATABASE_PASSWORD}/$DATABASE_PASSWORD/g /var/www/phplist/public_html/lists/config/config.php
sed -i s/{PHPMAILERHOST}/$PHPMAILERHOST/g /var/www/phplist/public_html/lists/config/config.php

# AJUSTANDO CONFIG DE MIGRACAO
cp /var/www/phplist/build/phinx.yml.sample /var/www/phplist/phinx.yml

sed -i s/{DATABASE_HOST}/$DATABASE_HOST/g /var/www/phplist/phinx.yml
sed -i s/{DATABASE_NAME}/$DATABASE_NAME/g /var/www/phplist/phinx.yml
sed -i s/{DATABASE_USER}/$DATABASE_USER/g /var/www/phplist/phinx.yml
sed -i s/{DATABASE_PASSWORD}/$DATABASE_PASSWORD/g /var/www/phplist/phinx.yml

#chown -R www-data. .

# BEHAT CONFIG
# cp /var/www/phplist/default.behat.yml /var/www/phplist/behat.yml
# mysqladmin --no-beep --user=$DATABASE_USER --host=mysql  --password=$DATABASE_PASSWORD create behat_db
# sed -i s/{SERVER_NAME}/$SERVER_NAME/g /var/www/phplist/behat.yml

echo "START CRON"
/etc/init.d/cron start

echo "START FPM"
/etc/init.d/php7.0-fpm start

echo "START NGINX"
nginx -g 'daemon off;'