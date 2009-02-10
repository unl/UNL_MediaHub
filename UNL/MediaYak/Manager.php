<?php
class UNL_MediaYak_Manager implements UNL_MediaYak_CacheableInterface, UNL_MediaYak_PostRunReplacements
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
     * @var UNL_mediaYak_User
     */
    public $user;
    
    public $output;
    
    public $options = array('view'=>'addmedia');
    
    protected static $replacements = array();
    
    public static $url;
    
    /**
     * MediaYak
     *
     * @var UNL_MediaYak
     */
    protected $mediayak;
    
    function __construct($dsn)
    {
        $this->mediayak = new UNL_MediaYak($dsn);
        
        $this->auth = UNL_Auth::factory('CAS');
        $this->auth->login();
        
        $this->options = array_merge($this->options, $_GET);
        
        $this->user = UNL_MediaYak_User::getByUid($this->auth->getUser());
    }
    
    function getCacheKey()
    {
        return false;
    }
    
    function preRun()
    {
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
        if (count($_POST)) {
            $this->handlePost();
        } else {
            switch($this->options['view']) {
            case 'feed':
                $this->showFeed();
                break;
            case 'feedmetadata':
                $this->editFeedMetaData();
                break;
            case 'permissions':
                $this->editPermissions();
                break;
            case 'feeds':
                $this->showFeeds($this->user);
                break;
            default:
            case 'addmedia':
                $this->addMedia();
                break;
            }
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
     * @return UNL_MediaYak_User
     */
    function getUser()
    {
        return $this->user;
    }
    
    function showMedia(UNL_MediaYak_Filter $filter = null)
    {
        $this->output[] = new UNL_MediaYak_MediaList($filter);
    }
    
    function editPermissions()
    {
        $feed = UNL_MediaYak_Feed::getById($_GET['feed_id']);
        $this->output[] = new UNL_MediaYak_UserList(new UNL_MediaYak_UserList_Filter_ByFeed($feed));
    }
    
    function showFeeds(UNL_MediaYak_User $user)
    {
        $this->output[] = $user->getFeeds();
    }
    
    
    public static function getURL($mixed = null)
    {
        if (is_object($mixed)) {
            switch(get_class($mixed)) {
                case 'UNL_MediaYak_Feed':
                    return UNL_MediaYak_Controller::$url.'manager/?view=feed&id='.$mixed->id;
            }
        }
        return UNL_MediaYak_Controller::$url.'manager/';
    }
    
    function showFeed()
    {
        $feed = UNL_MediaYak_Feed::getById($_GET['id']);
        $this->output[] = $feed;
        
        $filter = new UNL_MediaYak_MediaList_Filter_ByFeed($feed);
        $this->showMedia($filter);
    }
    
    function handlePost()
    {
        $post_target = $this->determinePostTarget();
        $this->filterPostData();
        switch($post_target) {
        case 'feed':
            // Insert or update a Feed/Channel
            if (isset($_POST['id'])) {
                // Update an existing feed.
                $feed = UNL_MediaYak_Feed::getById($_POST['id']);
                $feed->loadReference('UNL_MediaYak_Feed_NamespacedElements_itunes');
                $feed->synchronizeWithArray($_POST);
                $feed->save();
            } else {
                // Add a new feed for this user.
                $data = array_merge($_POST, array('datecreated'=>date('Y-m-d H:i:s'),
                                                  'uidcreated'=>$this->getUser()->uid));
                $feed = new UNL_MediaYak_Feed();
                $feed->fromArray($data);
                $feed->save();
                $feed->addUser($this->user);
            }
            $this->redirect('?view=feed&id='.$feed->id);
            break;
        case 'feed_media':
            // Add media to a feed/channel
            $feed = UNL_MediaYak_Feed::getById($_POST['feed_id']);
            if (!$feed->userHasPermission($this->user,
                                          UNL_MediaYak_Permission::getByID(
                                            UNL_MediaYak_Permission::USER_CAN_INSERT))) {
                throw new Exception('You do not have permission to do this.');
            }
            if (isset($_POST['id'])) {
                // all ok
                $media = UNL_MediaYak_Media::getById($_POST['id']);
                $media->synchronizeWithArray($_POST);
                $media->save();
            } else {
                // Insert a new piece of media
                $details = array('url'        => $_POST['url'],
                                 'title'      => $_POST['title'],
                                 'description'=> $_POST['description']);
                $media = $this->mediayak->addMedia($details);
            }
            $feed->addMedia($media);
            $this->redirect('?view=feed&id='.$feed->id);
            break;
        case 'feed_users':
            $feed = UNL_MediaYak_Feed::getById($_POST['feed_id']);
            if (!$feed->userHasPermission($this->user,
                                          UNL_MediaYak_Permission::getByID(
                                            UNL_MediaYak_Permission::USER_CAN_ADD_USER))) {
                throw new Exception('You do not have permission to add a user.');
            }
            if (!empty($_POST['uid'])) {
                $feed->addUser(UNL_MediaYak_User::getByUid($_POST['uid']));
            }
            $this->redirect('?view=feed&id='.$feed->id);
            break;
        }
    }
    
    /**
     * Determine what type of data is being saved.
     *
     * @return string
     */
    function determinePostTarget()
    {
        if (isset($_POST['__unlmy_posttarget'])) {
            return $_POST['__unlmy_posttarget'];
        }
        return false;
    }
    
    function filterPostData()
    {
        /** Remove linked records if they are not set anymore **/
        foreach (array('UNL_MediaYak_Feed_NamespacedElements_itunes'       => 'value',
                       'UNL_MediaYak_Feed_NamespacedElements_mrss'         => 'value',
                       'UNL_MediaYak_Feed_Media_NamespacedElements_itunes' => 'value',
                       'UNL_MediaYak_Feed_Media_NamespacedElements_mrss'   => 'value') as $relation=>$field) {
            if (isset($_POST[$relation])) {
                foreach ($_POST[$relation] as $key=>$values) {
                    if (empty($values[$field])) {
                        unset($_POST[$relation][$key]);
                    }
                }
            }
        }
        unset($_POST['__unlmy_posttarget']);
    }
    
    /**
     * Display a form for editing a feed's details
     *
     * @return void
     */
    function editFeedMetaData()
    {
        if (isset($_GET['id'])) {
            $this->output = new UNL_MediaYak_Feed_Form(UNL_MediaYak_Feed::getById($_GET['id']));
            return;
        }
        
        $this->output[] = new UNL_MediaYak_Feed_Form();
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
            $this->output[] = new UNL_MediaYak_Feed_Media_Form(UNL_MediaYak_Media::getById($_GET['id']));
            return;
        }
        
        $this->output[] = new UNL_MediaYak_Feed_Media_Form();
    }
    
    function redirect($location)
    {
        header('Location: '.$location);
        exit();
    }
}
