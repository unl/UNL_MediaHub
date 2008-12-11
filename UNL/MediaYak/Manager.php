<?php
class UNL_MediaYak_Manager implements UNL_MediaYak_CacheableInterface
{
    protected $auth;
    
    public $user;
    
    public $output;
    
    public static $url;
    
    protected $mediayak;
    
    function __construct($dsn)
    {
        $this->mediayak = new UNL_MediaYak($dsn);
        
        $this->auth = UNL_Auth::factory('CAS');
        $this->auth->login();
        
        $this->user = new UNL_MediaYak_User($this->auth->getUser());
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
        switch($this->options['view']) {
        default:
            $this->showFeeds($this->user);
            break;
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
    
    function showMedia()
    {
    }
    
    
    function showFeeds(UNL_MediaYak_User $user)
    {
        $this->output = $user->getFeeds();
    }
    
    
    public static function getURL()
    {
        return self::$url;
    }
    
    function showFeed()
    {
    }
    
    function userCanEditFeed($user, $feed)
    {
    }
    
    function editFeedMetaData()
    {
    }
    
    function editFeedPublishers($feed)
    {
    }
    
    function addMedia()
    {
    }
}

?>