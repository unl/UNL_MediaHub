<?php
echo '{ "id":'.$context->id.',
        "url":"'.$context->url.'",
        "title":"'.$context->title.'",
        "description":"'.trim(strip_tags($context->description)).'",
        "length":'.$context->length.',
        "image":"'.UNL_MediaYak_Controller::getURL($context).'/image",
        "type":"'.$context->type.'",
        "author":"'.$context->author.'",
        "datecreated":"'.$context->datecreated.'",
        "dateupdated":"'.$context->dateupdated.'"}';