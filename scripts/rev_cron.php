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
$orders = new UNL_MediaHub_RevOrderList(array('all_not_complete'=>true));

/**
 * Order status life cycle:
 * 1. STATUS_MEDIAHUB_CREATED (order was created by mediahub, not yet created in rev)
 * 2. created (created in rev)
 * ...
 * 3. complete (finished in rev, files ready)
 * 4. STATUS_MEDIAHUB_FINISHED
 */

$rev = UNL_MediaHub_RevAPI::getRevClient();

if (!$rev) {
    throw new Exception('Unable to get the Rev client', 500);
}

//Loop through them and check with Rev.com to see their status.
foreach ($orders->items as $order) {
    echo $order->id . ': ' . $order->status . PHP_EOL;
    echo "\tstatus: " . $order->status . PHP_EOL;
    
    $media = $order->getMedia();
    
    switch ($order->status) {
        case UNL_MediaHub_RevOrder::STATUS_MEDIAHUB_COMPLETE:
        case UNL_MediaHub_RevOrder::STATUS_ERROR:
            //Nothing to do here (in fact, this code should never execute)
            break;
        case UNL_MediaHub_RevOrder::STATUS_MEDIAHUB_CREATED:
            //We need to create the order in rev.com

            echo "\t creating a new rev.com order" . PHP_EOL;
            
            //First, we need to do our best to find the duration. Rev.com can't always do this.
            $duration = $media->findDuration();
            $seconds = null;
            if ($duration) {
                $seconds = $duration['seconds'];
            }
            
            $rev_input = $rev->uploadVideoUrl($media->url, null, $seconds);
            sleep(1); //be nice
            
            //create an order
            $caption_order = new \RevAPI\CaptionOrderSubmission($rev);
            $caption_order->addInput($rev_input);
            $caption_order->setOutputFormats(array('WebVtt'));
            
            //Generate a client ref
            $client_ref = 'id:'.$order->id.', co:'.$order->costobjectnumber.', uid:'.$order->uid;
            $caption_order->setClientRef($client_ref);

            //send the order
            try {
                $order_number = $caption_order->send();
                //Save the order number quickly, so that we don't lose it (if for example, the next api call fails).
                $order->rev_order_number = $order_number;
                $order->save();
                sleep(1); //be nice

                //Now fetch from the api to get the status
                $rev_order = $rev->getOrder($order_number);
                $order->status = $rev_order->getStatus();
                $order->dateupdated = date('Y-m-d H:i:s');
                $order->save();
                sleep(1); //be nice
            } catch (\RevAPI\Exception\RequestException $e) {
                //There was an error creating the request
                $message = $e->getRevCode() . ': ' . $e->getRevMessage();
                if (400 == $e->getCode()) {
                    //this was a bad input error from which we can not recover
                    $order->status = UNL_MediaHub_RevOrder::STATUS_ERROR;
                    $order->error_text = $message;
                    $order->dateupdated = date('Y-m-d H:i:s');
                    $order->save();
                }
                
                //Retry later.
                echo "\tERROR: (". $e->getCode() .")" . $message . PHP_EOL;
            }
            
            break;
        
        default:
            //fetch from the api to get the status
            $rev_order = $rev->getOrder($order->rev_order_number);
            sleep(1); //be nice

            //Update the rev status
            $order->status = $rev_order->getStatus();
            
            if ($rev_order->isComplete()) {
                echo "\t order is complete, fetching attachments..." . PHP_EOL;
                
                $attachments = $rev_order->getAttachments();
                sleep(1); //be nice

                $media_text_track = new UNL_MediaHub_MediaTextTrack();
                $media_text_track->media_id = $media->id;
                $media_text_track->source = 'rev';
                $media_text_track->revision_comment = 'Rev.com order: ' . $rev_order->getOrderNumber();
                $media_text_track->save();

                //Mark the order as complete
                $order->status = UNL_MediaHub_RevOrder::STATUS_MEDIAHUB_COMPLETE;

                //update the media to point to the new text track
                $media->media_text_tracks_id = $media_text_track->id;
                $media->dateupdated = date('Y-m-d H:i:s');
                $media->save();
                
                foreach ($attachments as $attachment) {
                    if (!$attachment->isMedia()) {
                        //Only save non-media attachments
                        
                        echo "\t creating a new text track file" . PHP_EOL;
                        $media_text_track_file = new UNL_MediaHub_MediaTextTrackFile();
                        $media_text_track_file = new UNL_MediaHub_MediaTextTrackFile();
                        $media_text_track_file->media_text_tracks_id = $media_text_track->id;
                        $media_text_track_file->kind = UNL_MediaHub_MediaTextTrackFile::KIND_CAPTION;
                        $media_text_track_file->format = UNL_MediaHub_MediaTextTrackFile::FORMAT_VTT;
                        $media_text_track_file->language = 'en';
                        $media_text_track_file->file_contents = $attachment->getContent('.vtt');
                        $media_text_track_file->save();
                    }
                    
                    sleep(1); //be nice
                }
            }
            
            //Save the order status;
            $order->dateupdated = date('Y-m-d H:i:s');
            $order->save();
    }
}

echo "--FINISHED--" . PHP_EOL;
