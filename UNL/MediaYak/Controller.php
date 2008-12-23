<?php
/**
 * Controller for the public frontend to the MediaYak system.
 * 
 * PHP version 5
 * 
 * @category  Events 
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2008 Regents of the University of Nebraska
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
    public $options;
    
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
    function __construct($dsn)
    {
        // Set up database
        $this->mediayak = new UNL_MediaYak($dsn);
        
        // Initialize default options
        $this->options = $_GET + array('view'   => null,
                                       'format' => null,
                                       );
                                       
        // Start authentication for comment system.
        include_once 'UNL/Auth.php';
        self::$auth = UNL_Auth::factory('SimpleCAS');
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
    function preRun()
    {
        switch ($this->options['format']) {
        case 'xml':
            // Send XML content-type headers, and assign XML output template.
            header('Content-type: text/xml');
            UNL_MediaYak_OutputController::setOutputTemplate('UNL_MediaYak_Controller', 'ControllerXML');    
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
        
        switch ($this->options['view']) {
        case 'media':
            $media = $this->findRequestedMedia($this->options);
            $this->showMedia($media);
            break;
        case 'feed':
            $this->showFeed();
            break;
        case 'search':
        default:
            $this->showLatestMedia();
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
        
        if (isset($_POST, $_POST['comment'])
            && self::isLoggedIn()) {
            
            $data = array('uid'      => self::$auth->getUID(),
                          'media_id' => $media->id,
                          'comment'  => $_POST['comment']);
            
            $comment = new UNL_MediaYak_Media_Comment();
            $comment->fromArray($data);
            $comment->save();
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
        
        if (isset(self::$replacements['title'])) {
            $me = str_replace($scanned->doctitle,
                              '<title>'.self::$replacements['title'].'</title>',
                              $me);
        }
        
        if (isset(self::$replacements['head'])) {
            $me = str_replace('</head>', self::$replacements['head'].'</head>', $me);
        }

        if (isset(self::$replacements['breadcrumbs'])) {
            $me = str_replace($scanned->breadcrumbs,
                              self::$replacements['breadcrumbs'],
                              $me);
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
    static function setReplacementData($field, $data)
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
                $url .= 'channels/'.url_encode($mixed->title);
                break;
            default:
                    
            }
        }
        
        $params = array_merge($params, $additional_params);
        
        $url .= '?';
        
        foreach ($params as $option=>$value) {
            if (!empty($value)) {
                $url .= "&amp;$option=$value";
            }
        }
        
        return trim($url, '?;&amp=');
    }
    
    /**
     * Prepare output for a list of media.
     *
     * @return void
     */
    function showLatestMedia()
    {
        $filter = null;
        if (isset($this->options['q'])) {
            $filter = new UNL_MediaYak_MediaList_Filter_TextSearch($this->options['q']);
        }
        
        $this->output = new UNL_MediaYak_MediaList($filter);
    }
    
    /**
     * Prepare output for a piece of media.
     *
     * @param UNL_MediaYak_Media $media The media to display.
     * 
     * @return void
     */
    function showMedia(UNL_MediaYak_Media $media)
    {
        $this->output = $media;
        if (!$this->output) {
            header('HTTP/1.0 404 Not Found');
            throw new Exception('Could not find that news release.');
        }
        $this->output->loadReference('UNL_MediaYak_Media_Comment');
    }
    
    function showFeed()
    {
        if (isset($this->options['feed_id'])) {
            $feed = UNL_MediaYak_Feed::getById($this->options['feed_id']);
        } elseif (isset($this->options['title'])) {
            $feed = UNL_MediaYak_Feed::getByTitle($this->options['title']);
        }
        $this->output[] = new UNL_MediaYak_FeedAndMedia($feed);
        
    }
}

