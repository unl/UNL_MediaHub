<?php
/**
 * Sample script for adding media.
 *
 * PHP version 5
 *
 * @category  Media
 * @package   UNL_MediaHub
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 */
if (!isset($_SERVER['argv'],$_SERVER['argv'][1])
    || $_SERVER['argv'][1] == '--help' || $_SERVER['argc'] != 4) {
        echo "This program requires 3 arguments.\n";
        echo "addMedia.php url title description\n\n";
        echo "Example: addMedia.php http://foo/media.mp4 'My Video' Blah\n";
} else {
    require_once 'UNL/Autoload.php';
    require_once dirname(__FILE__).'/../config.inc.php';
    
    $my = new UNL_MediaHub($dsn);
    $my->addMedia(array('url'   => $_SERVER['argv'][1],
                        'title' => $_SERVER['argv'][2],
                        'description' => $_SERVER['argv'][3]));
    echo "{$_SERVER['argv'][1]} has been added.\n";
}

exit();
