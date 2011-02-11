<?php
require_once dirname(__FILE__).'/../config.inc.php';

$mediayak = new UNL_MediaYak($dsn);

$subscriptions = new UNL_MediaYak_SubscriptionList();
$subscriptions->run();
if (count($subscriptions->items)) {    
    do {
        $media_added = 0;
        foreach ($subscriptions->items as $subscription) {
            echo 'Processing '.$subscription->filter_class.'('.$subscription->filter_option.'):'.PHP_EOL;
            $media_added += $subscription->process();
        }
    } while($media_added > 0);
}

echo 'Done'.PHP_EOL;