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
        if (!array_key_exists('unl_sso', $_COOKIE) && !$this->auth->isLoggedIn()) {
            return false;
        }

        if ($this->auth->isLoggedIn()) {
            return true;
        }

        $this->auth->login();

        return true;
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