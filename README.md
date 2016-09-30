#MediaHub

MediaHub is a video/audio aggregator for educational institutions, and is currently running at http://mediahub.unl.edu/

##Reasons why MediaHub might be a good idea for your institution:

* RSS Feeds for syndication to other sites (iTunes U)
 * iTunes and iTunes U Attributes included in RSS Feeds
* Captioning integration with Amara Universal Subtitles
* Support for multiple users and channels
* Complete branding control, with no external advertisements
* Local storage
* HTML5 video and audio support
* Caption support on all iOS devices
* Integrates with enterprise authentication systems
* Web Services and APIs, including XML, JSON, partial HTML views
* Sample scripts for importing from existing RSS Feeds

##Installation

### 1. Set up the initial configuration:
```bash
cp config.sample.php config.inc.php
cp www/sample.htaccess www/.htaccess
```

### 2. In `config.inc.php` be sure to:
* Set `UNL_MediaHub_Controller::$url` to base absolute URL of the application, with a trailing slash
* set `UNL_MediaHub::$dsn` to the proper DSN for the database. Format: mysql://username:password@localhost/database

### 3. In `.htaccess` be sure to:
* change `RewriteBase /` to the correct path. If mediahub is accessed from example.com, the path would be `/`. If it is accessed from `example.com/mediahub/www/` the path would be `/mediahub/www/`.

### 4. Run the update script
From commandline, run `php upgrade.php` to initialize the database. Run this command whenever the application is updated.

###Requirements:

* PHP 5, 7
* PDO Mysql
* mediainfo system package. This can be installed with `brew install mediainfo` or a similar command

###Testing:
Once installed, run this command from the project root:
```
php vendor/bin/phpunit --bootstrap tests/init.php tests
```

##Cache-busting
Versioning is handled with git, so the application MUST be checkout out with git for this to work

To update the version cache (which is used for cachebusting) run `php scripts/update_version.php`

To automate this, git hooks can be used.
symlink the sample file `update-version.sh`
From the directory `.git/hooks` run the following
`ln -s ../../update-version.sh post-checkout`
You may even want to have it triggered after post-merge (git pull)
`ln -s ../../update-version.sh post-merge`

###Sources:

* Audio and video player from http://mediaelementjs.com/
* jQuery from http://jquery.com/
