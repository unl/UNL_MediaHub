<?php
/**
 * Controller for the public frontend to the MediaHub system.
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
class UNL_MediaHub_Controller
    implements UNL_MediaHub_CacheableInterface, UNL_MediaHub_PostRunReplacements
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
                            'format' => 'html',
                            'mobile' => false,
    );
    
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
     * @var UNL_MediaHub_User
     */
    protected static $user;
    
    protected $view_map = array(
        'search'  => 'UNL_MediaHub_MediaList',
        'tags'    => 'UNL_MediaHub_MediaList',
        'default' => 'UNL_MediaHub_DefaultHomepage',
        'feeds'   => 'UNL_MediaHub_FeedList',
        'feed'    => 'UNL_MediaHub_FeedAndMedia',
        'dev'     => 'UNL_MediaHub_Developers',
        'live'    => 'UNL_MediaHub_Feed_LiveStream',
        );
    
    /**
     * Backend media system.
     *
     * @var UNL_MediaHub
     */
    protected $mediahub;
    
    /**
     * Construct a new controller.
     *
     * @param string $dsn Database connection string
     */
    function __construct($options, $dsn)
    {
        // Set up database
        $this->mediahub = new UNL_MediaHub($dsn);
        
        // Initialize default options
        $this->options = $options + $this->options;

        if ($this->options['format'] == 'html'
            && $this->options['mobile'] != 'no') {
            $this->options['mobile'] = self::isMobileClient();
        }
                                       
        // Start authentication for comment system.
        include_once 'UNL/Auth.php';
        self::$auth = UNL_Auth::factory('SimpleCAS');
        if (isset($_GET['logout'])) {
            self::$auth->logout();
        }

        if (self::$auth->isLoggedIn()) {
            self::$user = UNL_MediaHub_User::getByUid(self::$auth->getUser());
        }
    }

    public static function isMobileClient($options = array())
    {
        if (!isset($_SERVER['HTTP_ACCEPT'], $_SERVER['HTTP_USER_AGENT'])) {
            // We have no vars to check
            return false;
        }

        if (isset($_COOKIE['wdn_mobile'])
            && $_COOKIE['wdn_mobile'] == 'no') {
            // The user has a cookie set, requesting no mobile views
            return false;
        }

        if ( // Check the http_accept and user agent and see
            preg_match('/text\/vnd\.wap\.wml|application\/vnd\.wap\.xhtml\+xml/i', $_SERVER['HTTP_ACCEPT'])
                || 
            (preg_match('/'.
               'sony|symbian|nokia|samsung|mobile|windows ce|epoc|opera mini|' .
               'nitro|j2me|midp-|cldc-|netfront|mot|up\.browser|up\.link|audiovox|' .
               'blackberry|ericsson,|panasonic|philips|sanyo|sharp|sie-|' .
               'portalmmm|blazer|avantgo|danger|palm|series60|palmsource|pocketpc|' .
               'smartphone|rover|ipaq|au-mic|alcatel|ericy|vodafone\/|wap1\.|wap2\.|iPhone|Android' .
               '/i', $_SERVER['HTTP_USER_AGENT'])
           ) && !preg_match('/ipad/i', $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        }

        return false;
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
        if ($this->options['view'] == 'feed_image'
            || $this->options['view'] == 'media_image') {
            UNL_MediaHub_OutputController::setOutputTemplate('UNL_MediaHub_Controller', 'ControllerPartial');
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
            UNL_MediaHub_OutputController::setOutputTemplate('UNL_MediaHub_Controller', 'ControllerPartial');
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
                    $this->output[] = UNL_MediaHub_Feed_Image::getById($this->options['feed_id']);
                } else {
                    $this->output[] = UNL_MediaHub_Feed_Image::getByTitle($this->options['title']);
                }
                break;
            case 'media_image':
                $this->output[] = UNL_MediaHub_Media_Image::getById($this->options['id']);
                break;
            case 'default':
            case 'search':
            case 'tags':
            case 'feeds':
            case 'feed':
            case 'dev':
            case 'live':
                $class = $this->view_map[$this->options['view']];
                $this->output[] = new $class($this->options);
                break;
            default:
                throw new Exception('Unknown view', 404);
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
     * @return UNL_MediaHub_Media
     */
    function findRequestedMedia($options)
    {
        $media = false;
        if (isset($options['id'])) {
            $media = Doctrine::getTable('UNL_MediaHub_Media')->find($options['id']);
        }

        if (!empty($_POST)
            && self::isLoggedIn()) {
            $user = self::$auth->getUser();
            if (!empty($_POST['comment'])) {
                $data = array('uid'      => $user,
                              'media_id' => $media->id,
                              'comment'  => $_POST['comment']);
                
                $comment = new UNL_MediaHub_Media_Comment();
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
         
        $url = UNL_MediaHub_Controller::$url;
        
        if (is_object($mixed)) {
            switch (get_class($mixed)) {
            case 'UNL_MediaHub_Media':
                $url .= 'media/'.$mixed->id;
                break;
            case 'UNL_MediaHub_MediaList':
                $url = $mixed->getURL();
                break;
            case 'UNL_MediaHub_Feed':
                $url .= 'channels/'.$mixed->id;
                break;
            case 'UNL_MediaHub_FeedList':
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

