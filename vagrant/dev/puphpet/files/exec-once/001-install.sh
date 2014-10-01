BASEDIR="/var/www/html"

echo "installing mediahub"

#Go to the basedir to perform commands.
cd $BASEDIR

git submodule init
git submodule update

#copy .htaccess
if [ ! -f ${$BASEDIR}/www/.htaccess ]; then
    echo "Creating .htaccess"
    cp ${$BASEDIR}/www/sample.htaccess ${$BASEDIR}/www/.htaccess
fi

#copy config
if [ ! -f ${$BASEDIR}/config.inc.php ]; then
    echo "Creating config.inc.php"
    cp ${$BASEDIR}/config.sample.php ${$BASEDIR}/config.inc.php
fi

php ${$BASEDIR}/upgrade.php

echo "FINISHED installing mediahub"