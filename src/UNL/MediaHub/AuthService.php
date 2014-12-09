<?php
class UNL_MediaHub_AuthService
{
    protected static $instance = false;

    /**
     * @var UNL_Auth_SimpleCAS
     */
    public $auth;

    /**
     * @var UNL_MediaHub_User|NULL
     */
    public $user;
    
    protected function __construct()
    {
        $this->auth = UNL_Auth::factory('SimpleCAS');
        if (isset($_GET['logout'])) {
            $this->auth->logout();
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
     * @return bool|UNL_MediaHub_AuthService
     */
    public static function getInstance()
    {
        if (self::$instance) {
            return self::$instance;
        }
        
        self::$instance = new self;
        
        return self::$instance;
    }
}