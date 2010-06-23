<?php
if (isset($context->image_data, $context->image_type)) {
    header('Content-type:'.$context->image_type);
    header('Content-Length:'.$context->image_size);
    echo $context->image_data;
} else {
    echo "Error!';";
}

