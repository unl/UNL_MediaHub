<?php
class UNL_MediaHub_AuthService_UNL extends UNL_MediaHub_AuthService_Interface
{
    /**
     * @var UNL_Auth_SimpleCAS
     */
    protected $auth;

    protected $auto_auth_models = array(
        'UNL_MediaHub_MediaList',
        'UNL_MediaHub_DefaultHomepage',
        'UNL_MediaHub_FeedList',
        'UNL_MediaHub_FeedAndMedia',
        'media',
    );

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
     * @param null $current_model
     */
    public function autoLogin($current_model = NULL)
    {
        if (!array_key_exists('unl_sso', $_COOKIE)) {
            //No unl_sso cookie was found, no need to auto-login.
            return;
        }
        
        if ($this->isLoggedIn()) {
            //We are already logged in, no need to auto-login
            return;
        }
        
        if (!in_array($current_model, $this->auto_auth_models)) {
            //The current model doesn't support auto-login
            return;
        }
        
        //Everything looks good.  Log in!
        $this->auth->gatewayAuthentication();
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