<?php
UNL_MediaHub::$dsn = 'mysql://mediahub_test:mediahub_test@localhost/mediahub_test';
if (getenv('TRAVIS')) {
    UNL_MediaHub::$dsn = 'mysql://travis@127.0.0.1/mediahub_test';
}