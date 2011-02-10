<?php
require_once dirname(__FILE__).'/../config.inc.php';

$mediayak = new UNL_MediaYak($dsn);

$subscriptions = new UNL_MediaYak_SubscriptionList();
$subscriptions->run();
if (count($subscriptions->items)) {
    foreach ($subscriptions->items as $subscription) {
        echo 'Processing '.$subscription->filter_class.'('.$subscription->filter_option.'):'.PHP_EOL;
        var_dump($subscription->process());
    }
}

echo 'Done'.PHP_EOL;