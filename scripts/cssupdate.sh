php app/console cache:clear
php app/console assets:install
php app/console assetic:dump
find /var/www/src/Presentations/ -type d -exec chmod 777 {} \;
find /var/www/src/Presentations/ -type f -exec chmod 666 {} \;

echo "OK."