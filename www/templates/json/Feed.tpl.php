<?php
echo '{
        "id":'.$context->id.',
        "title":"'.$context->title.'",
        "description":"'.$context->description.'",
        "image":"'.UNL_MediaYak_Controller::getURL($context).'/image",
        "link":"'.UNL_MediaYak_Controller::getURL($context).'",
        "pubDate":"'.$context->datecreated.'",
        "uidcreated":"'.$context->uidcreated.'"}';