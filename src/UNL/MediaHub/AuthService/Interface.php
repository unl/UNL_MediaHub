<?php
abstract class UNL_MediaHub_AuthService_Interface
{
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
}