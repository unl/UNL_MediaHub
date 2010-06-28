<?php
/**
 * This package is intended to create PHP Class files (Objects) from 
 * Dreamweaver template (.dwt) files. It allows designers to create a
 * standalone Dreamweaver template for the website design, and developers
 * to use that design in php pages without interference.
 *
 * Similar to the way DB_DataObject works, the DWT package uses a 
 * Generator to scan a .dwt file for editable regions and creates an 
 * appropriately named class for that .dwt file with member variables for
 * each region.
 *
 * Once the objects have been generated, you can render a html page from 
 * the template.
 * 
 * $page = new UNL_DWT::factory('Template_style1');
 * $page->pagetitle = "Contact Information";
 * $page->maincontent = "Contact us by telephone at 111-222-3333.";
 * echo $page->toHtml();
 *
 * Parts of this package are modeled on (borrowed from) the PEAR package 
 * DB_DataObject.
 * 
 * PHP version 5
 * 
 * @category  Templates
 * @package   UNL_DWT
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @created   01/18/2006
 * @copyright 2008 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/package/UNL_DWT
 */

/**
 * Base class which understands Dreamweaver Templates.
 * 
 * @category  Templates
 * @package   UNL_DWT
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @created   01/18/2006
 * @copyright 2008 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/package/UNL_DWT
 */
class UNL_DWT
{
    
    public $__template;
    
    /**
     * Run-time configuration options
     *
     * @var array
     * @see UNL_DWT::setOption()
     */
    static public $options = array(
        'debug' => 0,
    );
    
    /**
     * Constructor
     */
    function __construct()
    {
        
    }
    
    /**
     * Returns the given DWT with all regions replaced with their assigned
     * content.
     * 
     * @return string
     */
    public function toHtml()
    {
        $options = &UNL_DWT::$options;
        if (!isset($this->__template)) {
            return '';
        }
        /*
        More Options for this method:
            Extend this to automatically generate the .tpl files and cache.
            Check for a cached copy of the template file.
            Connect to a template server and get the latest template copy.
            
            Ex: $p = file_get_contents("http://pear.unl.edu/UNL/Templates/server.php?template=".$this->__template);
        */
        $p = file_get_contents($options['tpl_location'].$this->__template);
        
        $regions = get_object_vars($this);
        return $this->replaceRegions($p, $regions);
    }
    
    /**
    * Replaces region tags within a template file wth their contents.
    * 
    * @param string $p       Page with DW Region tags.
    * @param array  $regions Associative array with content to replace.
    * 
    * @return string page with replaced regions
    */
    function replaceRegions($p, $regions)
    {
        UNL_DWT::debug('Replacing regions.', 'replaceRegions', 5);
        foreach ($regions as $region=>$value) {
            /* Replace the region with the replacement text */
            if (strpos($p, "<!--"." TemplateBeginEditable name=\"{$region}\" -->")) {
                $p = str_replace(UNL_DWT_between("<!--"." TemplateBeginEditable name=\"{$region}\" -->",
                                    "<!--"." TemplateEndEditable -->", $p),
                    $value, $p);
                UNL_DWT::debug("$region is replaced with $value.",
                               'replaceRegions', 5);
            } elseif (strpos($p, "<!--"." InstanceBeginEditable name=\"{$region}\" -->")) {
                $p = str_replace("<!--"." InstanceBeginEditable name=\"{$region}\" -->".
                                    UNL_DWT_between("<!--"." InstanceBeginEditable name=\"{$region}\" -->", "<!--"." InstanceEndEditable -->", $p).
                                    "<!--"." InstanceEndEditable -->", "<!--"." InstanceBeginEditable name=\"{$region}\" -->".$value."<!--"." InstanceEndEditable -->", $p);
                UNL_DWT::debug("$region is replaced with $value.", 'replaceRegions', 5);
            } else {
                UNL_DWT::debug("Could not find region $region!", 'replaceRegions', 3);
            }    
        }
        return $p;
    }
    
    
    /**
    * Create a new UNL_DWT object for the specified layout type
    *
    * @param string $type     the template type (eg "fixed")
    * @param array  $coptions an associative array of option names and values
    *
    * @return object  a new UNL_DWT.  A UNL_DWT_Error object on failure.
    *
    * @see UNL_DWT::setOption()
    */
    static function &factory($type, $coptions = false)
    {
        $options =& UNL_DWT::$options;
        
        include_once $options['class_location']."{$type}.php";
        
        if (!is_array($coptions)) {
            $coptions = array();
        }
        
        $classname = $options['class_prefix'].$type;
        
        if (!class_exists($classname)) {
            throw new UNL_DWT_Exception("Unable to include the {$options['class_location']}{$type}.php file.");
        }
        
        @$obj = new $classname;
        
        foreach ($coptions as $option => $value) {
            $test = $obj->setOption($option, $value);
        }
        
        return $obj;
    }
    
    /**
    * Sets options.
    * 
    * @param string $option Option to set
    * @param mixed  $value  Value to set for this option
    *
    * @return void
    */
    function setOption($option, $value)
    {
        self::$options[$option] = $value;
    }
    
    /* ----------------------- Debugger ------------------ */

    /**
     * Debugger. - use this in your extended classes to output debugging 
     * information.
     *
     * Uses UNL_DWT::debugLevel(x) to turn it on
     *
     * @param string $message message to output
     * @param string $logtype bold at start
     * @param string $level   output level
     * 
     * @return   none
     */
    static function debug($message, $logtype = 0, $level = 1)
    {
        if (empty(self::$options['debug'])  || 
            (is_numeric(self::$options['debug']) &&  self::$options['debug'] < $level)) {
            return;
        }
        // this is a bit flaky due to php's wonderfull class passing around crap..
        // but it's about as good as it gets..
        $class = (isset($this) && ($this instanceof UNL_DWT)) ? get_class($this) : 'UNL_DWT';
        
        if (!is_string($message)) {
            $message = print_r($message, true);
        }
        if (!is_numeric(self::$options['debug']) && is_callable(self::$options['debug'])) {
            return call_user_func(self::$options['debug'], $class, $message, $logtype, $level);
        }
        
        if (!ini_get('html_errors')) {
            echo "$class   : $logtype       : $message\n";
            flush();
            return;
        }
        if (!is_string($message)) {
            $message = print_r($message, true);
        }
        $colorize = ($logtype == 'ERROR') ? '<font color="red">' : '<font>';
        echo "<code>{$colorize}<strong>$class: $logtype:</strong> ". nl2br(htmlspecialchars($message)) . "</font></code><br />\n";
        flush();
    }

    /**
     * sets and returns debug level
     * eg. UNL_DWT::debugLevel(4);
     *
     * @param int $v level
     * 
     * @return void
     */
    function debugLevel($v = null)
    {
        if ($v !== null) {
            $r = isset(self::$options['debug']) ? self::$options['debug'] : 0;
            self::$options['debug']  = $v;
            return $r;
        }
        return isset(self::$options['debug']) ? self::$options['debug'] : 0;
    }

}

/**
 * exception used by the UNL_DWT class
 * 
 * @category  Templates
 * @package   UNL_DWT
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2008 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/package/UNL_DWT
 */
class UNL_DWT_Exception extends Exception
{
    
}
 
if (!function_exists('UNL_DWT_between')) {
    /**
     * Returns content between two strings
     *
     * @param string $start String which bounds the start
     * @param string $end   end collecting content when you see this
     * @param string $p     larger body of content to search
     * 
     * @return string
     */
    function UNL_DWT_between($start, $end, $p)
    {
        if (!empty($start) && strpos($p, $start)!=false) {
            $p = substr($p, strpos($p, $start)+strlen($start));
        }
        if (strpos($p, $end)!=false) {
            $p = substr($p, 0, strpos($p, $end));
        }
        return $p;
    }
}