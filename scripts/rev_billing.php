<?php
if (file_exists(dirname(__FILE__).'/../config.inc.php')) {
    require_once dirname(__FILE__).'/../config.inc.php';
} else {
    require_once dirname(__FILE__).'/../config.sample.php';
}

//Establish mediahub connection
$media_hub = new UNL_MediaHub();

$db = Doctrine_Manager::getInstance()->getCurrentConnection();

//Get all orders that have not been completed.
$orders = new UNL_MediaHub_RevOrderList(
    array(
        'for_billing'=>true,
        'after_date'=> date('Y-m-d H:i:s', strtotime('-1 month'))
    )
);

$csv = array();

//Headers
$csv[] = array(
    'id',
    'Rev Order Number',
    'Media ID',
    'Status',
    'Date Created',
    'Date Updated',
    'Media duration',
    'Estimated Cost',
    'Cost Object Number',
    'UID'
);


//Loop through them and check with Rev.com to see their status.
foreach ($orders->items as $order) {
    echo $order->id . ':' . $order->rev_order_number . PHP_EOL;

    //Headers
    $csv[] = array(
        $order->id,
        $order->rev_order_number,
        $order->media_id,
        $order->status,
        $order->datecreated,
        $order->dateupdated,
        $order->media_duration,
        $order->estimate,
        $order->costobjectnumber,
        $order->uid,
    ); 
}

$fp = fopen(__DIR__ . '/../tmp/rev_orders.csv', 'w');

foreach ($csv as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp);
