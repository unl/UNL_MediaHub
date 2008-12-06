<?php
class UNL_MediaYak_Manager
{
    protected $auth;
    
    public $user;
    
    public $output;
    
    function __construct($dsn)
    {
        $this->auth = UNL_Auth::factory('CAS');
        $this->auth->login();
        $this->user = new UNL_MediaYak_User($this->auth->getUser());
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