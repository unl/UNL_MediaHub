#!/usr/bin/env php
<?php
if (!isset($_SERVER['argv'],$_SERVER['argv'][1])
    || $_SERVER['argv'][1] == '--help' || $_SERVER['argc'] != 2) {
    echo "This program requires 1 argument.\n";
    echo "convert.php oldfile.shtml newfile.shtml\n\n";
    exit();
}

require_once 'UNL/Autoload.php';

if (!file_exists($_SERVER['argv'][1])) {
    echo "Filename does not exist!\n";
    exit();
}

UNL_Templates::$options['version'] = 3;
UNL_Templates::$options['templatedependentspath'] = '/Library/WebServer/Documents';


$p = new UNL_Templates_Scanner(file_get_contents($_SERVER['argv'][1]));



$new = UNL_Templates::factory('Fixed');
UNL_Templates::$options['templatedependentspath'] = '/Library/WebServer/Documents';


foreach ($p->getRegions() as $region) {
    if (count($region)) {
        $new->{$region->name} = $region->value;
    }
}
UNL_Templates::$options['templatedependentspath'] = 'paththatdoesnotexist!';

echo $new;
?>