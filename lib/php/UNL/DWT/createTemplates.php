#!/usr/bin/php -q
<?php
/**
 * Tool to generate objects for dreamweaver template files.
 * 
 * PHP version 5
 *  
 * @package   UNL_DWT
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @created   01/18/2006
 * @copyright 2008 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/package/UNL_DWT
 */

// since this version doesnt use overload, 
// and I assume anyone using custom generators should add this..
define('UNL_DWT_NO_OVERLOAD',1);
ini_set('display_errors',true);
require_once 'UNL/DWT/Generator.php';

if (!ini_get('register_argc_argv')) {
    throw new Exception("\nERROR: You must turn register_argc_argv On in your php.ini file for this to work\neg.\n\nregister_argc_argv = On\n\n");
}

if (!@$_SERVER['argv'][1]) {
    throw new Exception("\nERROR: createTemplates.php usage:\n\nC:\php\pear\UNL\DWT\createTemplates.php example.ini\n\n");
}

$config = parse_ini_file($_SERVER['argv'][1], true);
foreach($config as $class=>$values) {
    if ($class == 'UNL_DWT') {
        UNL_DWT::$options = $values;
    }
}

if (empty(UNL_DWT::$options)) {
    throw new Exception("\nERROR: could not read ini file\n\n");
}
set_time_limit(0);
//UNL_DWT::debugLevel(1);
$generator = new UNL_DWT_Generator;
$generator->start();
 
