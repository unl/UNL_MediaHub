<?php

class UNL_MediaYak_OutputController
{
    static $output_template       = array();
    
    static $template_path         = '';
    
    static $directory_separator   = '_';
    
    static $classname_replacement = 'UNL_MediaYak_';
    
    static protected $cache;
    
    static function display($mixed, $return = false)
    {
        if (is_array($mixed)) {
            return self::displayArray($mixed, $return);
        }
        
        if (is_object($mixed)) {
            return self::displayObject($mixed, $return);
        }
        
        if ($return) {
            return $mixed;
        }
        
        echo $mixed;
        return true;
    }
    
    static public function setCacheInterface(UNL_MediaYak_CacheInterface $cache)
    {
        self::$cache = $cache;
    }
    
    static public function getCacheInterface()
    {
        if (!isset(self::$cache)) {
            self::setCacheInterface(new UNL_MediaYak_Cache());
        }
        return self::$cache;
    }
    
    static function displayArray($mixed, $return = false)
    {
        $output = '';
        foreach ($mixed as $m) {
            if ($return) {
                $output .= self::display($m, true);
            } else {
                self::display($m, true);
            }
        }
        
        if ($return) {
            return $output;
        }
        
        return true;
    }
    
    static function displayObject($object, $return = false)
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
                
                if ($return) {
                    $data = self::sendObjectOutput($object, $return);
                } else {
                    self::sendObjectOutput($object, $return);
                    $data = ob_get_contents();
                }
                
                if ($key !== false) {
                    self::getCacheInterface()->save($data, $key);
                }
                ob_end_clean();
            }
            
            if ($object instanceof UNL_MediaYak_PostRunReplacements) {
                $data = $object->postRun($data);
            }
            
            if ($return) {
                return $data;
            }
            
            echo $data;
            return true;
        }
        
        return self::sendObjectOutput($object, $return);

    }
    
    static protected function sendObjectOutput(&$object, $return = false)
    {
        include_once 'Savant3.php';
        $savant = new Savant3();
        foreach (get_object_vars($object) as $key=>$var) {
            $savant->$key = $var;
        }
        if (in_array('ArrayAccess', class_implements($object))) {
            foreach ($object->toArray() as $key=>$var) {
                $savant->$key = $var;
            }
        }
        if ($object instanceof Exception) {
            $savant->code    = $object->getCode();
            $savant->line    = $object->getLine();
            $savant->file    = $object->getFile();
            $savant->message = $object->getMessage();
            $savant->trace   = $object->getTrace();
        }
        $templatefile = self::getTemplateFilename(get_class($object));
        if (file_exists($templatefile)) {
            if ($return) {
                ob_start();
                $savant->display($templatefile);
                $output = ob_get_clean();
                return $output;
            }
            $savant->display($templatefile);
            return true;
        }
        
        throw new Exception('Sorry, '.$templatefile.' was not found.');
    }
    
    static function getTemplateFilename($class)
    {
        if (isset(self::$output_template[$class])) {
            $class = self::$output_template[$class];
        }
        
        $class = str_replace(array(self::$classname_replacement,
                                   self::$directory_separator),
                             array('',
                                   DIRECTORY_SEPARATOR),
                             $class);
        
        if (!empty(self::$template_path)) {
            $templatefile = self::$template_path
                          . DIRECTORY_SEPARATOR . $class . '.tpl.php';
        } else {
            $templatefile = 'templates' . DIRECTORY_SEPARATOR
                          . $class . '.tpl.php';
        }
        
        return $templatefile;
    }
    
    static public function setOutputTemplate($class_name, $template_name)
    {
        if (isset($template_name)) {
            self::$output_template[$class_name] = $template_name;
        }
        return self::getTemplateFilename($class_name);
    }
    
    static public function setDirectorySeparator($separator)
    {
        self::$directory_separator = $separator;
    }
    
    static public function setClassNameReplacement($replacement)
    {
        self::$classname_replacement = $replacement;
    }
}

