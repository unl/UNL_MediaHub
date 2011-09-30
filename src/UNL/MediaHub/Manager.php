<?php
class UNL_MediaHub_Manager implements UNL_MediaHub_CacheableInterface, UNL_MediaHub_PostRunReplacements
{
    /**
     * The auth object.
     *
     * @var UNL_Auth
     */
    protected $auth;
    
    /**
     * The user that's logged in.
     *
     * @var UNL_MediaHub_User
     */
    protected static $user;
    
    public $output;
    
    public $options = array('view'=>'addmedia');

    protected $view_map = array(
        'feedmetadata'    => 'UNL_MediaHub_Feed_Form',
        'permissions'     => 'UNL_MediaHub_Feed_UserList',
        'feeds'           => 'UNL_MediaHub_User_FeedList',
        'subscriptions'   => 'UNL_MediaHub_User_Subscriptions',
        'addsubscription' => 'UNL_MediaHub_Subscription_Form',
        'uploadprogress'  => 'UNL_MediaHub_Feed_Media_FileUpload_Progress',
        'mediapreview'    => 'UNL_MediaHub_Media_Preview',
        );
    
    protected static $replacements = array();
    
    public static $url;
    
    /**
     * MediaHub
     *
     * @var UNL_MediaHub
     */
    protected $mediahub;
    
    function __construct($options = array(), $dsn)
    {
        $this->mediahub = new UNL_MediaHub($dsn);
        
        $this->auth = UNL_Auth::factory('SimpleCAS');
        $this->auth->login();
        if (isset($_GET['logout'])) {
            $this->auth->logout();
            exit();
        }
        
        $this->options = $options + $this->options;
        
        self::$user = UNL_MediaHub_User::getByUid($this->auth->getUser());
    }
    
    function getCacheKey()
    {
        return false;
    }
    
    function preRun($cached)
    {
        switch ($this->options['format']) {
        case 'partial':
            UNL_MediaHub_OutputController::setOutputTemplate('UNL_MediaHub_Manager', 'ControllerPartial');
            break;
        }
        return true;
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
    
    function run()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $this->handlePost();
            }

            if (isset($this->options['view'], $this->view_map[$this->options['view']])) {
                $class = $this->view_map[$this->options['view']];
                $this->output[] = new $class($this->options);
                return;
            }

            switch($this->options['view']) {
                case 'feed':
                    $this->showFeed();
                    break;
                case 'addmedia':
                    $this->addMedia();
                    // intentional no break
                default:
                    $class = $this->view_map['feeds'];
                    $this->output[] = new $class($this->options);
                    break;
            }
        } catch (Exception $e) {
            $this->output = $e;
        }
    }
    
    /**
     * Determines if the user is logged in.
     *
     * @return bool
     */
    function isLoggedIn()
    {
        return $this->auth->isLoggedIn();
    }
    
    /**
     * Get the user
     *
     * @return UNL_MediaHub_User
     */
    public static function getUser()
    {
        return self::$user;
    }
    
    function showMedia(UNL_MediaHub_Filter $filter = null)
    {
        $options           = $this->options;
        $options['filter'] = $filter;

        $this->output[] = new UNL_MediaHub_MediaList($options + $this->options);
    }

    /**
     * Get the path to the directory where uploads are stored
     *
     * @return string
     */
    public static function getUploadDirectory()
    {
        return dirname(dirname(dirname(dirname(__FILE__)))).'/www/uploads';
    }

    public static function getURL($mixed = null, $additional_params = array())
    {
        $params = array();

        if (is_object($mixed)) {
            switch(get_class($mixed)) {
                case 'UNL_MediaHub_Feed':
                    $params['view'] = 'feed';
                    $params['id']   = $mixed->id;
            }
        }

        $params = array_merge($params, $additional_params);

        return UNL_MediaHub_Controller::addURLParams(UNL_MediaHub_Controller::$url.'manager/', $params);
    }
    
    function showFeed()
    {
        if (empty($this->options['id'])) {
            throw new Exception('No feed selected to show.');
        }

        $feed = UNL_MediaHub_Feed::getById($this->options['id']);
        if (!($feed && $feed->userHasPermission(self::$user, UNL_MediaHub_Permission::getByID(
                                                    UNL_MediaHub_Permission::USER_CAN_INSERT)))) {
            throw new Exception('You do not have permission for this feed.');
        }

        $this->output[] = $feed;

        $filter = new UNL_MediaHub_MediaList_Filter_ByFeed($feed);
        $this->showMedia($filter);

    }
    
    /**
     * This function accepts info posted to the system.
     *
     */
    function handlePost()
    {
        $handler = new UNL_MediaHub_Manager_PostHandler($this->options, $_POST, $_FILES);
        $handler->setMediaHub($this->mediahub);
        return $handler->handle();
    }
    
    function editFeedPublishers($feed)
    {
    }
    
    /**
     * Show the form to add media to a feed.
     *
     * @return void
     */
    function addMedia()
    {
        if (isset($_GET['id'])) {
            $this->output[] = new UNL_MediaHub_Feed_Media_Form(UNL_MediaHub_Media::getById($_GET['id']));
            return;
        }
        
        $this->output[] = new UNL_MediaHub_Feed_Media_Form();
    }
}
