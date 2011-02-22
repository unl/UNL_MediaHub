<?php
/**
 * Controller for the public frontend to the MediaYak system.
 * 
 * PHP version 5
 * 
 * @category  Events 
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_MediaYak_Controller
    implements UNL_MediaYak_CacheableInterface, UNL_MediaYak_PostRunReplacements
{
    /**
     * Auth object for the client.
     *
     * @var UNL_Auth
     */
    protected static $auth;

    /**
     * Array of options
     *
     * @var array
     */
    public $options = array('view'   => 'default',
                            'format' => 'html');
    
    /**
     * UNL template style to use.
     *
     * @var string
     */
    public $template = 'Fixed';
    
    /**
     * Any output prepared by this controller will go here. This could be 
     * any type of data, string, array or object.
     *
     * @var mixed
     */
    public $output;
    
    /**
     * URL to this controller.
     *
     * @var string
     */
    public static $url;
    
    /**
     * URL to a thumbnail generator for media files.
     *
     * @var string
     */
    public static $thumbnail_generator;
    
    static protected $replacements;
    
    /**
     * currently logged in user, if any
     * 
     * @var UNL_MediaYak_User
     */
    protected static $user;
    
    protected $view_map = array(
        'search'  => 'UNL_MediaYak_MediaList',
    	'tags'    => 'UNL_MediaYak_MediaList',
        'default' => 'UNL_MediaYak_DefaultHomepage',
        'feeds'   => 'UNL_MediaYak_FeedList',
        'feed'    => 'UNL_MediaYak_FeedAndMedia',
        'dev'     => 'UNL_MediaYak_Developers',
        );
    
    /**
     * Backend media system.
     *
     * @var UNL_MediaYak
     */
    protected $mediayak;
    
    /**
     * Construct a new controller.
     *
     * @param string $dsn Database connection string
     */
    function __construct($options, $dsn)
    {
        // Set up database
        $this->mediayak = new UNL_MediaYak($dsn);
        
        // Initialize default options
        $this->options = $options + $this->options;
                                       
        // Start authentication for comment system.
        include_once 'UNL/Auth.php';
        self::$auth = UNL_Auth::factory('SimpleCAS');
        if (isset($_GET['logout'])) {
            self::$auth->logout();
        }

        if (self::$auth->isLoggedIn()) {
            self::$user = UNL_MediaYak_User::getByUid(self::$auth->getUser());
        }
    }
    
    /**
     * Check if the user is logged in or not.
     *
     * @return bool
     */
    static function isLoggedIn()
    {
        if (self::$auth->isLoggedIn()) {
            return true;
        }
        
        return false;
    }

    static function getUser()
    {
        return self::$user;
    }
    
    /**
     * Get the cache key for the data prepared by the controller
     *
     * @return string|false
     */
    function getCacheKey()
    {
        return false;
    }
    
    /**
     * function called before output, cached or otherwise is sent.
     *
     * @return bool
     */
    function preRun($cached)
    {
        if ($this->options['view'] == 'feed_image') {
            UNL_MediaYak_OutputController::setOutputTemplate('UNL_MediaYak_Controller', 'ControllerPartial');
            return false;
        }
        // Send headers for CORS support so calendar bits can be pulled remotely
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: X-Requested-With');
        if (isset($_SERVER['REQUEST_METHOD'])
            && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            // short circuit execution for CORS OPTIONS reqeusts
            exit();
        }
        switch ($this->options['format']) {
        case 'xml':
        case 'rss':
            // Send XML content-type headers, and assign XML output template.
            header('Content-type: text/xml');
            break;
        case 'partial':
            UNL_MediaYak_OutputController::setOutputTemplate('UNL_MediaYak_Controller', 'ControllerPartial');
            break;
        case 'json':
            header('Content-type: application/json');
            break;
        default:
            break;
        }

        return true;
    }
    
    /**
     * Main hub for the controller.
     * 
     * This will be called when cached output cannot be found.
     * 
     * @return void
     */
    function run()
    {
        if (self::isLoggedIn()) {
            // We're golden.
        }

        try {
            switch ($this->options['view']) {
            case 'media':
                $this->output[] = $this->findRequestedMedia($this->options);
                break;
            case 'feed_image':
                if (isset($this->options['feed_id'])) {
                    $this->output[] = UNL_MediaYak_Feed_Image::getById($this->options['feed_id']);
                } else {
                    $this->output[] = UNL_MediaYak_Feed_Image::getByTitle($this->options['title']);
                }
                break;
            case 'default':
            case 'search':
            case 'tags':
            case 'feeds':
            case 'feed':
            case 'dev':
                $class = $this->view_map[$this->options['view']];
                $this->output[] = new $class($this->options);
                break;
            }
        } catch(Exception $e) {
            $this->output[] = $e;
        }
    }
    
    /**
     * Find a specific piece of media.
     *
     * @param array $options Associative array of options $options['id']
     * 
     * @return UNL_MediaYak_Media
     */
    function findRequestedMedia($options)
    {
        $media = false;
        if (isset($options['id'])) {
            $media = Doctrine::getTable('UNL_MediaYak_Media')->find($options['id']);
        }

        if (!empty($_POST)
            && self::isLoggedIn()) {
            $user = self::$auth->getUser();
            if (!empty($_POST['comment'])) {
                $data = array('uid'      => $user,
                              'media_id' => $media->id,
                              'comment'  => $_POST['comment']);
                
                $comment = new UNL_MediaYak_Media_Comment();
                $comment->fromArray($data);
                $comment->save();
            }

            if (!empty($_POST['tags'])) {
                foreach (explode(',', $_POST['tags']) as $tag) {
                    $media->addTag(trim($tag));
                }
            }
        }


        if ($media) {
            return $media;
        }
        
        throw new Exception('Cannot determine the media to display.');
    }
    
    /**
     * Called after run - with all output contents.
     *
     * @param string $me The content from the outputcontroller
     * 
     * @return string
     */
    function postRun($me)
    {
        $scanned = new UNL_Templates_Scanner($me);

        if (isset(self::$replacements['title'], $scanned->doctitle)) {
            $me = str_replace($scanned->doctitle,
                              '<title>'.self::$replacements['title'].'</title>',
                              $me);
            unset(self::$replacements['title']);
        }
        
        if (isset(self::$replacements['head'])) {
            $me = str_replace('</head>', self::$replacements['head'].'</head>', $me);
        }

        if (isset(self::$replacements['breadcrumbs'], $scanned->breadcrumbs)) {
            $me = str_replace($scanned->breadcrumbs,
                              self::$replacements['breadcrumbs'],
                              $me);
            unset(self::$replacements['breadcrumbs']);
        }
        
        return $me;
    }
    
    /**
     * Allows you to set dynamic data when cached output is sent.
     *
     * @param string $field Area to be replaced
     * @param string $data  Data to replace the field with.
     * 
     * @return void
     */
    function setReplacementData($field, $data)
    {
        switch ($field) {
        case 'title':
        case 'head':
        case 'breadcrumbs':
            self::$replacements[$field] = $data;
            break;
        }
    }
    
    /**
     * Get the URL for the system or a specific object this controller can display.
     *
     * @param mixed $mixed             Optional object to get a URL for.
     * @param array $additional_params Extra parameters to adjust the URL.
     * 
     * @return string
     */
    function getURL($mixed = null, $additional_params = array())
    {
        $params = array();
         
        $url = UNL_MediaYak_Controller::$url;
        
        if (is_object($mixed)) {
            switch (get_class($mixed)) {
            case 'UNL_MediaYak_Media':
                $url .= 'media/'.$mixed->id;
                break;
            case 'UNL_MediaYak_MediaList':
                $url = $mixed->getURL();
                break;
            case 'UNL_MediaYak_Feed':
                $url .= 'channels/'.$mixed->id;
                break;
            case 'UNL_MediaYak_FeedList':
                $url .= 'channels/';
                break;
            default:
                    
            }
        }
        
        $params = array_merge($params, $additional_params);
        
        return self::addURLParams($url, $params);
    }

    /**
     * Add unique querystring parameters to a URL
     * 
     * @param string $url               The URL
     * @param array  $additional_params Additional querystring parameters to add
     * 
     * @return string
     */
    public static function addURLParams($url, $additional_params = array())
    {
        $params = array();
        if (strpos($url, '?') !== false) {
            list($url, $existing_params) = explode('?', $url);
            $existing_params = explode('&', $existing_params);
            foreach ($existing_params as $val) {
                list($var, $val) = explode('=', $val);
                $params[$var] = $val;
            }
        }

        $params = array_merge($params, $additional_params);

        $url .= '?';
        
        foreach ($params as $option=>$value) {
            if ($option == 'driver') {
                continue;
            }
            if ($option == 'format'
                && $value = 'html') {
                continue;
            }
            if (!empty($value)) {
                $url .= "&$option=$value";
            }
        }
        $url = str_replace('?&', '?', $url);
        return trim($url, '?;=');
    }
}

