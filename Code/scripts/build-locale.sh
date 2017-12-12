cur_dir=`dirname $0`

# create virtual host
cat /dev/null > /etc/apache2/sites-available/presentations.conf
echo "<VirtualHost *:80>" >> /etc/apache2/sites-available/presentations.conf
echo "   DocumentRoot "/var/www/src/Presentations/web"" >> /etc/apache2/sites-available/presentations.conf
echo "   ServerName presentations.dev" >> /etc/apache2/sites-available/presentations.conf
echo "   ServerAlias *.presentations.dev" >> /etc/apache2/sites-available/presentations.conf
echo "   # This should be omitted in the production environment" >> /etc/apache2/sites-available/presentations.conf
echo "   SetEnv APPLICATION_ENV development" >> /etc/apache2/sites-available/presentations.conf
echo "   <Directory "/var/www/src/Presentations/web">" >> /etc/apache2/sites-available/presentations.conf
echo "       php_admin_value error_reporting 30711" >> /etc/apache2/sites-available/presentations.conf
echo "       Options Indexes MultiViews FollowSymLinks" >> /etc/apache2/sites-available/presentations.conf
echo "       AllowOverride All" >> /etc/apache2/sites-available/presentations.conf
echo "       Order allow,deny" >> /etc/apache2/sites-available/presentations.conf
echo "       Allow from all" >> /etc/apache2/sites-available/presentations.conf
echo "   </Directory>" >> /etc/apache2/sites-available/presentations.conf
echo "    ErrorLog /var/log/apache2/presentations.log" >> /etc/apache2/sites-available/presentations.conf
echo "</VirtualHost>" >> /etc/apache2/sites-available/presentations.conf

# create host
grep -q -F '127.0.0.1 presentations.dev' /etc/hosts || echo '127.0.0.1 presentations.dev' >> /etc/hosts

# enable virtual host
mda=/etc/apache2/sites-enabled/presentations.conf
if [ ! -h $mda ]; then
    ln -s /etc/apache2/sites-available/presentations.conf /etc/apache2/sites-enabled/presentations.conf
    service apache2 reload
fi

# create cache and logs directories
# clear their contents if there are any
# set permissions
mkdir -p ${cur_dir}/../app/cache
mkdir -p ${cur_dir}/../app/logs
rm -rf ${cur_dir}/../app/cache/*
rm -rf ${cur_dir}/../app/logs/*
HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX ${cur_dir}/../app/cache ${cur_dir}/../app/logs ${cur_dir}/../web/uploads
setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX ${cur_dir}/../app/cache ${cur_dir}/../app/logs ${cur_dir}/../web/uploads
