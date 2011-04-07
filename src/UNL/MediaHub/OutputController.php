<?php

class UNL_MediaHub_OutputController extends Savvy_Turbo
{
    
    function __construct($options = array())
    {
        Savvy_ClassToTemplateMapper::$classname_replacement = 'UNL_MediaHub_';
        parent::__construct();
    }

    static public function setOutputTemplate($class_name, $template_name)
    {
        if (isset($template_name)) {
            Savvy_ClassToTemplateMapper::$output_template[$class_name] = $template_name;
        }
    }
}

