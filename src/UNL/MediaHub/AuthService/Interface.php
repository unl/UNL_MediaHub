<?php
abstract class UNL_MediaHub_AuthService_Interface
{
    /**
     * @var UNL_MediaHub_User|NULL
     */
    public $user;

    public function __construct() {}

    /**
     * @return bool
     */
    abstract public function isLoggedIn();
    
    abstract public function login();
    
    abstract public function logout();

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