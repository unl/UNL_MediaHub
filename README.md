# MediaHub

MediaHub is a video/audio aggregator for educational institutions, and is currently running at http://mediahub.unl.edu/

## Reasons why MediaHub might be a good idea for your institution:

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

## Installation

### 1. Set up the initial configuration:
```bash
cp config.sample.php config.inc.php
cp www/sample.htaccess www/.htaccess
```

### 2. In `config.inc.php` be sure to:
* Set `UNL_MediaHub_Controller::$url` to base absolute URL of the application, with a trailing slash
* set `UNL_MediaHub::$dsn` to the proper DSN for the database. Format: mysql://username:password@localhost/database

### 3. In `.htaccess` be sure to:
Change `RewriteBase /` to the correct path. If mediahub is accessed from example.com, the path would be `/`. If it is accessed from `example.com/mediahub/www/` the path would be `/mediahub/www/`.

### 4. Run composer install
From commandline, run the following command: `composer install` to download and install packages used by mediahub

### 5. Run the update script
From commandline, run `php upgrade.php` to initialize the database. Run this command whenever the application is updated.

### 6. Initialize upload directories
Create the `www/uploads/tmp` directory if it does not already exist.

Assign proper permissions to allow the web server to write to those directories. For development, the following commands should be fine.

```bash
chmod 777 www/uploads
chmod 777 www/uploads/tmp
```

### 7. Install the WDN framework
Install the `wdn` directory to `www/wdn` for the latest include files.
This can be done with a symlink like `ln -s /abolute-path-to-wdn-dir www/wdn`

### Requirements:

* PHP 5, 7
* PDO Mysql
* mediainfo system package. This can be installed with `brew install mediainfo` or a similar command

### Testing:
Once installed, run this command from the project root:
```bash
php vendor/bin/phpunit --bootstrap tests/init.php tests
```

## Cache-busting
Versioning is handled with git, so the application MUST be checkout out with git for this to work

To update the version cache (which is used for cachebusting) run `php scripts/update_version.php`

To automate this, git hooks can be used.
symlink the sample file `update-version.sh`
From the directory `.git/hooks` run the following
`ln -s ../../update-version.sh post-checkout`
You may even want to have it triggered after post-merge (git pull)
`ln -s ../../update-version.sh post-merge`

### Sources:

* Audio and video player from http://mediaelementjs.com/
* jQuery from http://jquery.com/

## User Auth
MediaHub currently supports UNL PHP CAS or Apache mod_shib by setting `UNL_MediaHub_AuthService::$provider` in config.inc.php

### UNL PHP CAS example
```
UNL_MediaHub_AuthService::$provider = new UNL_MediaHub_AuthService_UNL();
```
### Apache mod_shib example
```
$shibSettings = array (
  'shibLoginURL' => 'https://localhost/Shibboleth.sso/Login',
  'shibLogoutURL' => 'https://localhost/Shibboleth.sso/Logout',
  'appBaseURL' => UNL_MediaHub_Controller::$url,
  'userAttributes' => array(
    'eduPersonAssurance',
    'eduPersonScopedAffiliation',
    'eduPersonAffiliation',
    'sn',
    'givenName',
    'surname',
    'email',
    'displayName',
    'eduPersonPrincipalName'
  )
);
UNL_MediaHub_AuthService::$provider = new UNL_MediaHub_AuthService_ModShib($shibSettings);
```
