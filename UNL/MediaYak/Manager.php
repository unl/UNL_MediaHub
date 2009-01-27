<?php
class UNL_MediaYak_Manager implements UNL_MediaYak_CacheableInterface
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
    
    public $options = array('view'=>'feeds');
    
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
            case 'addmedia':
                $this->addMedia();
                break;
            case 'permissions':
                $this->editPermissions();
                break;
            case 'feeds':
            default:
                $this->showFeeds($this->user);
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
        switch($this->determinePostTarget()) {
        case 'feed':
            // Insert or update a Feed/Channel
            if (isset($_POST['id'])) {
                // Update an existing feed.
                $feed = UNL_MediaYak_Feed::getById($_POST['id']);
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
            if ($media = UNL_MediaYak_Media::getByURL($_POST['url'])) {
                // all ok
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
        $this->output = new UNL_MediaYak_Feed_Media_Form();
    }
    
    function redirect($location)
    {
        header('Location: '.$location);
        exit();
    }
}
