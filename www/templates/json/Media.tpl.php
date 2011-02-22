<?php
echo '{
        title:"'.$context->title.'",
        description:"'.trim(strip_tags($context->description)).'",
        image:"'.UNL_MediaYak_Controller::getURL($context).'/image"}';