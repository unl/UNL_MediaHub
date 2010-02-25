<?php

class UNL_MediaYak_OutputController extends Savvy
{
    static $output_template       = array();
    
    static protected $cache;
    
    function __construct($options = array())
    {
        self::setClassNameReplacement('UNL_MediaYak_');
        parent::__construct();
    }
    
    static public function setCacheInterface(UNL_MediaYak_CacheInterface $cache)
    {
        self::$cache = $cache;
    }
    
    static public function getCacheInterface()
    {
        if (!isset(self::$cache)) {
            self::setCacheInterface(new UNL_MediaYak_CacheInterface_CacheLite());
        }
        return self::$cache;
    }
    
    public function renderObject($object)
    {
        if ($object instanceof UNL_MediaYak_CacheableInterface) {
            $key = $object->getCacheKey();
            
            // We have a valid key to store the output of this object.
            if ($key !== false && $data = self::getCacheInterface()->get($key)) {
                // Tell the object we have cached data and will output that.
                $object->preRun(true);
            } else {
                // Content should be cached, but none could be found.
                //flush();
                ob_start();
                $object->preRun(false);
                $object->run();
                
                $data = parent::renderObject($object);
                
                if ($key !== false) {
                    self::getCacheInterface()->save($data, $key);
                }
                ob_end_clean();
            }
            
            if ($object instanceof UNL_MediaYak_PostRunReplacements) {
                $data = $object->postRun($data);
            }
            
            return $data;
        }
        
        return parent::renderObject($object);

    }
    
    static public function setOutputTemplate($class_name, $template_name)
    {
        if (isset($template_name)) {
            self::$output_template[$class_name] = $template_name;
        }
    }
    
    static public function setDirectorySeparator($separator)
    {
        self::$directory_separator = $separator;
    }
    
    static public function setClassNameReplacement($replacement)
    {
        Savvy_ClassToTemplateMapper::$classname_replacement = $replacement;
    }
}

