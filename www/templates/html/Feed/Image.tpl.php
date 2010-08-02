<?php
if (!empty($context->image_data) && !empty($context->image_type)) {
    header('Content-type:'.$context->image_type);
    header('Content-Length:'.$context->image_size);
    echo $context->image_data;
} else {
    header('Content-type:image/png');
    echo file_get_contents(dirname(__FILE__).'/../../../manager/templates/css/images/iconTV.png');
}

