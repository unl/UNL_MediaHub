<?php
abstract class UNL_MediaHub_AuthService_Interface
{
    protected $auto_auth_models = array(
        'UNL_MediaHub_MediaList',
        'UNL_MediaHub_DefaultHomepage',
        'UNL_MediaHub_FeedList',
        'UNL_MediaHub_FeedAndMedia',
        'media',
    );

    /**
     * @var UNL_MediaHub_User|NULL
     */
    public $user;

    public function __construct() {}

    /**
     * Determine if a user is currently logged in
     * 
     * @return bool
     */
    abstract public function isLoggedIn();

    /**
     * Force a user to log in
     * 
     * @return mixed
     */
    abstract public function login();

    /**
     * Log a user out of the system
     * 
     * @return mixed
     */
    abstract public function logout();
    
    /**
     * Preform auto-login logic if required.
     * 
     * @param null $current_model
     * @return mixed
     */
    abstract public function autoLogin($current_model = NULL);

    /**
     * @param UNL_MediaHub_User $user
     */
    public function setUser(UNL_MediaHub_User $user = NULL)
    {
        $this->user = $user;
    }

    /**
     * @return NULL|UNL_MediaHub_User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return NULL|String
     */
    public function getUserDisplayName()
    {
        $displayUser = $this->getUser();
        return !empty($displayUser) && !empty($displayUser->uid) ? $displayUser->uid : '';
    }
}
