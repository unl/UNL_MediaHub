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
     * @var UNL_MediaYak_User
     */
    protected static $user;
    
    public $output;
    
    public $options = array('view'=>'addmedia');

    protected $view_map = array(
        'feedmetadata' => 'UNL_MediaYak_Feed_Form',
        'permissions'  => 'UNL_MediaYak_Feed_UserList',
        'feeds'        => 'UNL_MediaYak_User_FeedList'
        );
    
    protected static $replacements = array();
    
    public static $url;
    
    /**
     * MediaYak
     *
     * @var UNL_MediaYak
     */
    protected $mediayak;
    
    function __construct($options = array(), $dsn)
    {
        $this->mediayak = new UNL_MediaYak($dsn);
        
        $this->auth = UNL_Auth::factory('SimpleCAS');
        $this->auth->login();
        if (isset($_GET['logout'])) {
            $this->auth->logout();
            exit();
        }
        
        $this->options = $options + $this->options;
        
        self::$user = UNL_MediaYak_User::getByUid($this->auth->getUser());
    }
    
    function getCacheKey()
    {
        return false;
    }
    
    function preRun($cached)
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
            if (count($_POST)) {
                $this->handlePost();
            }

            switch($this->options['view']) {
                case 'feed':
                    $this->showFeed();
                    break;
                case 'feedmetadata':
                case 'permissions':
                case 'feeds':
                    $class = $this->view_map[$this->options['view']];
                    $this->output[] = new $class($this->options);
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
     * @return UNL_MediaYak_User
     */
    public static function getUser()
    {
        return self::$user;
    }
    
    function showMedia(UNL_MediaYak_Filter $filter = null)
    {
        $options           = $this->options;
        $options['filter'] = $filter;

        $this->output[] = new UNL_MediaYak_MediaList($options + $this->options);
    }
    
    public static function getURL($mixed = null, $additional_params = array())
    {
        $params = array();

        if (is_object($mixed)) {
            switch(get_class($mixed)) {
                case 'UNL_MediaYak_Feed':
                    $params['view'] = 'feed';
                    $params['id']   = $mixed->id;
            }
        }

        $params = array_merge($params, $additional_params);

        return UNL_MediaYak_Controller::addURLParams(UNL_MediaYak_Controller::$url.'manager/', $params);
    }
    
    function showFeed()
    {
        $feed = UNL_MediaYak_Feed::getById($_GET['id']);
        if ($feed && $feed->userHasPermission(self::$user, UNL_MediaYak_Permission::getByID(
                                                    UNL_MediaYak_Permission::USER_CAN_INSERT))){
            $this->output[] = $feed;
            
            $filter = new UNL_MediaYak_MediaList_Filter_ByFeed($feed);
            $this->showMedia($filter);
        } else {
            throw new Exception('You do not have permission for this feed.');
        }
    }
    
    /**
     * This function accepts info posted to the system.
     *
     */
    function handlePost()
    {
        $post_target = $this->determinePostTarget();
        $this->filterPostData();
        switch($post_target) {
        case 'feed':
            if (isset($_FILES['image_file'])
                && is_uploaded_file($_FILES['image_file']['tmp_name'])) {
                $_POST['image_data'] = file_get_contents($_FILES['image_file']['tmp_name']);
                $_POST['image_type'] = $_FILES['image_file']['type'];
                $_POST['image_size'] = $_FILES['image_file']['size'];
            }

            // Insert or update a Feed/Channel
            if (isset($_POST['id'])) {
                // Update an existing feed.
                $feed = UNL_MediaYak_Feed::getById($_POST['id']);
                $feed->synchronizeWithArray($_POST);
                $feed->save();
            } else {
                // Add a new feed for this user.
                $feed = UNL_MediaYak_Feed::addFeed($_POST, self::getUser());
            }
            $this->redirect('?view=feed&id='.$feed->id);
            break;
        case 'feed_media':
            // Add media to a feed/channel
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
                $media->synchronizeWithArray($_POST);
                $media->save();
            }
            
            if (!empty($_POST['feed_id'])) {
                if (is_array($_POST['feed_id'])) {
                    $feed_ids = array_keys($_POST['feed_id']);
                } else {
                    $feed_ids = array($_POST['feed_id']);
                }
                foreach ($feed_ids as $feed_id) {
                    $feed = UNL_MediaYak_Feed::getById($feed_id);
                    if (!$feed->userHasPermission(self::getUser(),
                                                  UNL_MediaYak_Permission::getByID(
                                                    UNL_MediaYak_Permission::USER_CAN_INSERT))) {
                        throw new Exception('You do not have permission to do this.');
                    }
                    $feed->addMedia($media);
                }
            }
            
            if (!empty($_POST['new_feed'])) {
                $data = array('title'       => $_POST['new_feed'],
                              'description' => $_POST['new_feed']);
                $feed = UNL_MediaYak_Feed::addFeed($data, self::getUser());
                $feed->addMedia($media);
            }
            
            if (isset($feed, $feed->id)) {
                $this->redirect('?view=feed&id='.$feed->id);
            }
            // @todo clean cache for this feed!
            $this->redirect(UNL_MediaYak_Manager::getURL());
            break;
        case 'feed_users':
            $feed = UNL_MediaYak_Feed::getById($_POST['feed_id']);
            if (!$feed->userHasPermission(self::getUser(),
                                          UNL_MediaYak_Permission::getByID(
                                            UNL_MediaYak_Permission::USER_CAN_ADD_USER))) {
                throw new Exception('You do not have permission to add a user.');
            }
            if (!empty($_POST['uid'])) {
                if (!empty($_POST['delete'])) {
                    $feed->removeUser(UNL_MediaYak_User::getByUid($_POST['uid']));
                } else {
                    $feed->addUser(UNL_MediaYak_User::getByUid($_POST['uid']));
                }
            }
            $this->redirect('?view=feed&id='.$feed->id);
            break;
        case 'delete_media':
            $feed = UNL_MediaYak_Feed::getById($_POST['feed_id']);
            $media = UNL_MediaYak_Media::getById($_POST['media_id']);
            if ($feed->hasMedia($media)
                && $feed->userHasPermission(self::getUser(), UNL_MediaYak_Permission::getByID(
                                            UNL_MediaYak_Permission::USER_CAN_DELETE))) {
                $media->delete();
            }
            $this->redirect('?view=feed&id='.$feed->id);
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
    
    /**
     * Remove POST data that should not be handled.
     *
     * @return void
     */
    function filterPostData()
    {
        /** Remove linked records if they are not set anymore **/
        foreach (array('UNL_MediaYak_Feed_NamespacedElements_itunes'        => 'value',
                       'UNL_MediaYak_Feed_NamespacedElements_media'         => 'value',
                       'UNL_MediaYak_Feed_Media_NamespacedElements_itunesu' => 'value',
                       'UNL_MediaYak_Feed_Media_NamespacedElements_itunes'  => 'value',
                       'UNL_MediaYak_Feed_Media_NamespacedElements_media'   => 'value') as $relation=>$field) {
            if (isset($_POST[$relation])) {
                foreach ($_POST[$relation] as $key=>$values) {
                    if (empty($values[$field])
                        && empty($values['attributes'])) {
                        unset($_POST[$relation][$key]);
                    }
                }
            }
        }
        unset($_POST['__unlmy_posttarget']);
        unset($_POST['MAX_FILE_SIZE']);
        unset($_POST['submit_existing']);
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
    
    /**
     * Redirect to the location given.
     *
     * @param string $location URL to redirect to.
     */
    function redirect($location)
    {
        header('Location: '.$location);
        exit();
    }
}
