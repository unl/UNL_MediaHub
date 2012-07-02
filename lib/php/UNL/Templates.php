<?php
/**
 * Object oriented interface to create UNL Template based HTML pages.
 * 
 * PHP version 5
 *  
 * @category  Templates
 * @package   UNL_Templates
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @author    Ned Hummel <nhummel2@unl.edu>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/
 */

/**
 * Utilizes the UNL_DWT Dreamweaver template class.
 */
require_once 'UNL/DWT.php';

/**
 * Allows you to create UNL Template based HTML pages through an object 
 * oriented interface.
 * 
 * Install on your PHP server with:
 * pear channel-discover pear.unl.edu
 * pear install unl/UNL_Templates
 * 
 * <code>
 * <?php
 * require_once 'UNL/Templates.php';
 * $page                  = UNL_Templates::factory('Fixed');
 * $page->titlegraphic    = '<h1>UNL Templates</h1>';
 * $page->maincontentarea = 'Hello world!';
 * $page->loadSharedcodeFiles();
 * echo $page;
 * </code>
 * 
 * @category  Templates
 * @package   UNL_Templates
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @author    Ned Hummel <nhummel2@unl.edu>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/
 */
class UNL_Templates extends UNL_DWT
{
    const VERSION2 = 2;
    const VERSION3 = 3;
    const VERSION3x1 = '3.1';
    
    /**
     * Cache object for output caching
     * 
     * @var UNL_Templates_CachingService
     */
    static protected $cache;
    
    static public $options = array(
        'debug'                  => 0,
        'sharedcodepath'         => 'sharedcode',
        'templatedependentspath' => '',
        'cache'                  => array(),
        'version'                => self::VERSION3,
        'timeout'                => 5
    );
    
    /**
     * The version of the templates we're using.
     * 
     * @var UNL_Templates_Version
     */
    static public $template_version;
    
    /**
     * Construct a UNL_Templates object
     */
    public function __construct()
    {
        date_default_timezone_set(date_default_timezone_get());
        if (empty(self::$options['templatedependentspath'])) {
            self::$options['templatedependentspath'] = $_SERVER['DOCUMENT_ROOT'];
        }
    }
    
    /**
     * Initialize the configuration for the UNL_DWT class
     * 
     * @return void
     */
    public static function loadDefaultConfig()
    {
        self::$options['version'] = str_replace('.', 'x', self::$options['version']);
        include_once 'UNL/Templates/Version'.self::$options['version'].'.php';
        $class = 'UNL_Templates_Version'.self::$options['version'];
        self::$template_version = new $class();
        UNL_DWT::$options = array_merge(UNL_DWT::$options, self::$template_version->getConfig());
    }
    
    /**
     * The factory returns a template object for any UNL Template style requested:
     *  * Fixed
     *  * Liquid
     *  * Popup
     *  * Document
     *  * Secure
     *  * Unlaffiliate
     * 
     * <code>
     * $page = UNL_Templates::factory('Fixed');
     * </code>
     *
     * @param string $type     Type of template to get, Fixed, Liquid, Doc, Popup
     * @param mixed  $coptions Options for the constructor
     * 
     * @return UNL_Templates
     */
    static function &factory($type, $coptions = false)
    {
        UNL_Templates::loadDefaultConfig();
        return parent::factory($type, $coptions);
    }
    
    /**
     * Attempts to connect to the template server and grabs the latest cache of the
     * template (.tpl) file. Set options for Cache_Lite in self::$options['cache']
     * 
     * @return string
     */
    function getCache()
    {
        $cache = self::getCachingService();
        $cache_key = self::$options['version'].self::$options['templatedependentspath'].$this->__template;
        // Test if there is a valid cache for this template
        if ($data = $cache->get($cache_key)) {
            // Content is in $data
            self::debug('Using cached version from '.
                         date('Y-m-d H:i:s', $cache->lastModified()), 'getCache', 3);
        } else { // No valid cache found
            if ($data = self::$template_version->getTemplate($this->__template)) {
                self::debug('Updating cache.', 'getCache', 3);
                $data = $this->makeIncludeReplacements($data);
                $cache->save($data, $cache_key);
            } else {
                // Error getting updated version of the templates.
                self::debug('Could not connect to template server. ' . PHP_EOL .
                             'Extending life of template cache.', 'getCache', 3);
                $cache->extendLife();
                $data = $cache->get($this->__template);
            }
        }
        return $data;
    }
    
    /**
     * Loads standard customized content (sharedcode) files from the filesystem.
     * 
     * @return void
     */
    function loadSharedcodeFiles()
    {    
        $includes = array(
                            'footercontent'         => 'footer.html',
                            'contactinfo'           => 'footerContactInfo.html',
                            'navlinks'              => 'navigation.html',
                            'leftcollinks'          => 'relatedLinks.html',
                            'optionalfooter'        => 'optionalFooter.html',
                            'collegenavigationlist' => 'unitNavigation.html',
                            );
        foreach ($includes as $element=>$filename) {
            if (file_exists(self::$options['sharedcodepath'].'/'.$filename)) {
                $this->{$element} = file_get_contents(self::$options['sharedcodepath'].'/'.$filename);
            }
        }
    }


    /**
     * Add a link within the head of the page.
     * 
     * @param string $href       URI to the resource
     * @param string $relation   Relation of this link element (alternate)
     * @param string $relType    The type of relation (rel)
     * @param array  $attributes Any additional attribute=>value combinations
     * 
     * @return void
     */
    function addHeadLink($href, $relation, $relType = 'rel', array $attributes = array())
    {
        $attributeString = '';
        foreach ($attributes as $name=>$value) {
            $attributeString .= $name.'="'.$value.'" ';
        }    
    
        $this->head .= '<link '.$relType.'="'.$relation.'" href="'.$href.'" '.$attributeString.' />'.PHP_EOL;
    
    }

    /**
     * Add a (java)script to the page.
     *
     * @param string $url  URL to the script
     * @param string $type Type of script text/javascript
     * 
     * @return void
     */
    function addScript($url, $type = 'text/javascript')
    {
        $this->head .= '<script type="'.$type.'" src="'.$url.'"></script>'.PHP_EOL;
    }

    /**
     * Adds a script declaration to the page.
     *
     * @param string $content The javascript you wish to add.
     * @param string $type    Type of script tag.
     * 
     * @return void
     */
    function addScriptDeclaration($content, $type = 'text/javascript')
    {
        $this->head .= '<script type="'.$type.'">//<![CDATA['.PHP_EOL.$content.PHP_EOL.'//]]></script>'.PHP_EOL;
    }

    /**
     * Add a style declaration to the head of the document.
     * <code>
     * $page->addStyleDeclaration('.course {font-size:1.5em}');
     * </code>
     *
     * @param string $content CSS content to add
     * @param string $type    type attribute for the style element
     * 
     * @return void
     */
    function addStyleDeclaration($content, $type = 'text/css')
    {
        $this->head .= '<style type="'.$type.'">'.$content.'</style>'.PHP_EOL;
    }
    
    /**
     * Add a link to a stylesheet.
     *
     * @param string $url   Address of the stylesheet, absolute or relative
     * @param string $media Media target (screen/print/projector etc)
     * 
     * @return void
     */
    function addStyleSheet($url, $media = 'all')
    {
        $this->addHeadLink($url, 'stylesheet', 'rel', array('media'=>$media, 'type'=>'text/css'));
    }

    /**
     * Returns the page in HTML form.
     * 
     * @return string THe full HTML of the page.
     */
    function toHtml()
    {
        $p       = $this->getCache();
        $regions = get_object_vars($this);
        return $this->replaceRegions($p, $regions);
    }
    
    /**
     * returns this template as a string.
     *
     * @return string
     */
    function __toString()
    {
        return $this->toHtml();
    }
    
    
    /**
     * Populates templatedependents files
     * 
     * Replaces the template dependent include statements with the corresponding 
     * files from the /ucomm/templatedependents/ directory. To specify the location
     * of your templatedependents directory, use something like
     * $page->options['templatedependentspath'] = '/var/www/';
     * and set the path to the directory containing /ucomm/templatedependents/
     *
     * @param string $p Page to make replacements in
     * 
     * @return string
     */
    function makeIncludeReplacements($p)
    {
        return self::$template_version->makeIncludeReplacements($p);
    }
    
    /**
     * Debug handler for messages.
     *
     * @param string $message Message to send to debug output
     * @param int    $logtype Which log to send this to
     * @param int    $level   The threshold to send this message or not.
     * 
     * @return void
     */
    static function debug($message, $logtype = 0, $level = 1)
    {
        UNL_DWT::$options['debug'] = self::$options['debug'];
        parent::debug($message, $logtype, $level);
    }
    
    /**
     * Cleans the cache.
     *
     * @param mixed $o Pass a cached object to clean it's cache, or a string id.
     *
     * @return bool true if cache was successfully cleared.
     */
    public function cleanCache($object = null)
    {
        return self::getCachingService()->clean($object);
    }
    
    static public function setCachingService(UNL_Templates_CachingService $cache)
    {
        self::$cache = $cache;
    }
    
    static public function getCachingService()
    {
        if (!isset(self::$cache)) {
            $file  = 'UNL/Templates/CachingService/Null.php';
            $class = 'UNL_Templates_CachingService_Null';

            $fp = @fopen('UNL/Cache/Lite.php', 'r', true);
            if ($fp) {
                fclose($fp);
                $file  = 'UNL/Templates/CachingService/UNLCacheLite.php';
                $class = 'UNL_Templates_CachingService_UNLCacheLite';
            } else {
                $fp = @fopen('Cache/Lite.php', 'r', true);
                if ($fp) {
                    fclose($fp);
                    $file  = 'UNL/Templates/CachingService/CacheLite.php';
                    $class = 'UNL_Templates_CachingService_CacheLite';
                }
            }

            include_once $file;
            self::$cache = new $class(self::$options['cache']);
        }
        return self::$cache;
    }

    static public function getDataDir()
    {
        if (file_exists(dirname(__FILE__).'/../../data/pear.unl.edu/UNL_Templates')) {
            // new pear2 package & pyrus installation layout
            return dirname(__FILE__).'/../../data/pear.unl.edu/UNL_Templates';
        }

        if (file_exists(dirname(__FILE__).'/../../data/tpl_cache')) {
            // svn checkout
            return realpath(dirname(__FILE__).'/../../data');
        }

        if ('@DATA_DIR@' != '@DATA_DIR'.'@') {
            // pear/pyrus installation
            return '@DATA_DIR@/UNL_Templates/data/';
        }

        throw new Exception('Cannot determine data directory!');
    }
}
