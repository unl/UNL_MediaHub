<?php
class UNL_MediaHub_AuthService_UNL extends UNL_MediaHub_AuthService_Interface
{
    /**
     * @var UNL_Auth_SimpleCAS
     */
    protected $auth;

    public function __construct()
    {
        $this->auth = UNL_Auth::factory('SimpleCAS');
        
        $this->auth->handleSingleLogOut();
        
        if (isset($_GET['logout'])) {
            $this->auth->logout();
            exit();
        }

        if ($this->isLoggedIn()) {
            $this->user = UNL_MediaHub_User::getByUid($this->auth->getUser());
        }
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        if ($this->auth->isLoggedIn()) {
            return true;
        }

        return false;
    }

    public function login()
    {
        $this->auth->login();
    }

    public function logout()
    {
        $this->auth->logout();
    }
}