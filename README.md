#MediaHub

MediaHub is a video/audio aggregator for educational institutions, and is currently running at http://mediahub.unl.edu/

##Reasons why MediaHub might be a good idea for your institution:

* RSS Feeds for syndication to other sites (iTunes U)
 * iTunes and iTunes U Attributes included in RSS Feeds
* Captioning integration with Amara Universal Subtitles
* Support for multiple users and channels
* Complete branding control, with no external advertisements
* Local storage
* HTML5 video player
* Caption support on all iOS devices
* Integrates with enterprise authentication systems
* Web Services and APIs, including XML, JSON, partial HTML views

##Installation

```bash
cp config.sample.php config.inc.php
cp sample.htaccess .htaccess
```

Database:
Create a database for mediahub
```bash
mysql -u root
```

```sql
CREATE DATABASE mediahub;
GRANT ALL ON mediahub.* TO mediahub@localhost IDENTIFIED BY 'mediahub';
```

```bash
mysql -u mediahub -p mediahub < UNL_MediaHub/data/mediahub.sql
```

username: mediahub
password: mediahub
Create the database using the `data/mediahub.sql`

###Requirements:

* PHP 5
* PDO Mysql

###Sources:

* Audio and video player from http://mediaelementjs.com/
* jQuery from http://jquery.com/
