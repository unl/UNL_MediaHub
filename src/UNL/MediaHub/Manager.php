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
    
    public $options = array('view'=>'home', 'format'=>'html');

    protected $view_map = array(
        'feedmetadata'    => 'UNL_MediaHub_Feed_Form',
        'permissions'     => 'UNL_MediaHub_Feed_UserList',
        'feeds'           => 'UNL_MediaHub_User_FeedList',
        'subscriptions'   => 'UNL_MediaHub_User_Subscriptions',
        'addsubscription' => 'UNL_MediaHub_Subscription_Form',
        'mediapreview'    => 'UNL_MediaHub_Media_Preview',
        'uploadcomplete'  => 'UNL_MediaHub_Feed_Media_FileUpload_Complete',
        'upload'          => 'UNL_MediaHub_Feed_Media_FileUpload',
        'home'            => 'UNL_MediaHub_Manager_ManagerHome',
        'addmedia'        => 'UNL_MediaHub_Feed_Media_Form',
        );
    
    protected static $replacements = array();
    
    public static $url;
    
    /**
     * MediaHub
     *
     * @var UNL_MediaHub
     */
    protected static $mediahub;

    /**
     * Directory where uploaded files are stored
     * 
     * @var string
     */
    protected static $uploadDirectory;

    function __construct($options = array())
    {
        self::$mediahub = new UNL_MediaHub();
        
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
        case 'barebones':
            UNL_MediaHub_OutputController::setOutputTemplate('UNL_MediaHub_Manager', 'ControllerBarebones');
            break;
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

    /**
     * Get the path to the directory where uploads are stored
     *
     * @return string
     */
    public static function getUploadDirectory()
    {
        if (!isset(self::$uploadDirectory)) {
            // set the default upload directory
            self::setUploadDirectory(dirname(dirname(dirname(dirname(__FILE__)))).'/www/uploads');
        }

        return self::$uploadDirectory;
    }

    /**
     * Get the path to the directory where temp (uncompleted) uploads are stored
     *
     * @return string
     */
    public static function getTmpUploadDirectory()
    {
        return self::getUploadDirectory() . '/tmp';
    }

    /**
     * Setter for the upload directory where media will be save
     *
     * @param string $uploadDirectory Directory on the filesystem
     * @throws Exception
     */
    public static function setUploadDirectory($uploadDirectory)
    {
        if (!is_dir($uploadDirectory)) {
            throw new Exception('Invalid upload directory '.$uploadDirectory);
        }

        self::$uploadDirectory = $uploadDirectory;
    }

    public static function getURL()
    {
        return UNL_MediaHub_Controller::$url.'manager/';
    }
    
    /**
     * This function accepts info posted to the system.
     *
     */
    function handlePost()
    {
        $handler = new UNL_MediaHub_Manager_PostHandler($this->options, $_POST, $_FILES);
        $handler->setMediaHub(self::$mediahub);
        return $handler->handle();
    }
    
    function editFeedPublishers($feed)
    {
    }
}
