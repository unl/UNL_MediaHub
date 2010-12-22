<?php
echo '{
        title:"'.$context->title.'",
        description:"'.$context->description.'",
        image:"'.UNL_MediaYak_Controller::getURL($context).'/image"}';