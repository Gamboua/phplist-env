#!/bin/bash

set -e

# AJUSTANDO CONFIG DE MIGRACAO
cp /var/www/phplist/build/phinx.yml.sample /var/www/phplist/phinx.yml

sed -i s/{DATABASE_HOST}/$DATABASE_HOST/g /var/www/phplist/phinx.yml
sed -i s/{DATABASE_NAME}/$DATABASE_NAME/g /var/www/phplist/phinx.yml
sed -i s/{DATABASE_USER}/$DATABASE_USER/g /var/www/phplist/phinx.yml
sed -i s/{DATABASE_PASSWORD}/$DATABASE_PASSWORD/g /var/www/phplist/phinx.yml

./build/wait-for-it.sh mysql:3306 -t 300

php vendor/bin/phinx migrate -e local > migration.log
