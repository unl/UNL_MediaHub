<?php
class UNL_MediaHub_AuthService_Mock extends UNL_MediaHub_AuthService_Interface
{
    public function __construct() {}

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        if ($this->getUser()) {
            return true;
        }
        
        return false;
    }

    public function login()
    {
        //Do nothing.
    }

    public function logout()
    {
        $this->user = null;
    }
}