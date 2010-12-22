<?php
echo '{
        title:"'.$context->title.'",
        description:"'.strip_tags($context->description).'",
        image:"'.UNL_MediaYak_Controller::getURL($context).'/image"}';