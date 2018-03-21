<?php
echo '{
        "id":'.$context->id.',
        "title":"'.UNL_MediaHub::escape($context->title).'",
        "description":"'.UNL_MediaHub::escape($context->description).'",
        "image":"'.UNL_MediaHub_Controller::getURL($context).'/image",
        "link":"'.UNL_MediaHub_Controller::getURL($context).'",
        "pubDate":"'.$context->datecreated.'",
        "uidcreated":"'.$context->uidcreated.'",
        "media_count":'.$context->UNL_MediaHub_Media->count().'}';