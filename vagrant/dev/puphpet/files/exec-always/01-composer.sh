BASEDIR="/var/www/html"

echo "running composer install"

#Go to the basedir to perform commands.
cd $BASEDIR

/usr/local/bin/composer install
