<?php
require_once dirname(__FILE__).'/../config.inc.php';

$mediayak = new UNL_MediaYak($dsn);

$subscriptions = new UNL_MediaYak_SubscriptionList();
$subscriptions->run();

if (count($subscriptions->items)) {    
    do {
        // Set a counter so we know if any media was added
        $media_added = 0;
        foreach ($subscriptions->items as $subscription) {
            /**
             * @var $subscription UNL_MediaYak_Subscription 
             */
            echo 'Processing '.$subscription->filter_class.'('.$subscription->filter_option.'):'.PHP_EOL;
            $media_added += $subscription->process();
        }

        // Continue to process subscriptions until no new media has been added to channels
    } while($media_added > 0);
}

echo 'Done'.PHP_EOL;