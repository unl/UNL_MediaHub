<?php
class UNL_MediaYak_Manager implements UNL_MediaYak_CacheableInterface
{
    protected $auth;
    
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
        return void;
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
            case 'feeds':
            default:
                $this->showFeeds($this->user);
                break;
            }
        }
    }
    
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
    
    
    function showFeeds(UNL_MediaYak_User $user)
    {
        $this->output = $user->getFeeds();
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
            $feed = UNL_MediaYak_Feed::getById($_POST['feed_id']);
            if ($media = UNL_MediaYak_Media::getByURL($_POST['url'])) {
                // all ok
            } else {
                // Insert a new piece of media
                $details = array('url'=>$_POST['url'],
                                 'title'=>$_POST['title'],
                                 'description'=>$_POST['description']);
                $media = $this->mediayak->addMedia($details);
            }
            $feed->addMedia($media);
            $this->redirect('?view=feed&id='.$feed->id);
            break;
        }
    }
    
    function determinePostTarget()
    {
        if (isset($_POST['__unlmy_posttarget'])) {
            return $_POST['__unlmy_posttarget'];
        }
        return false;
    }
    
    function userCanEditFeed($user, $feed)
    {
    }
    
    function editFeedMetaData()
    {
        if (isset($_GET['id'])) {
            $this->output = new UNL_MediaYak_Feed_Form(UNL_MediaYak_Feed::getById($_GET['id']));
            return;
        }
        
        $this->output[] = new UNL_MediaYak_Feed_Form($feed);
    }
    
    function editFeedPublishers($feed)
    {
    }
    
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
