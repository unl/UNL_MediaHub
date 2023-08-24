<?php
class UNL_MediaHub_AuthService_UNL extends UNL_MediaHub_AuthService_Interface
{
    public static $cert_path = '/etc/pki/tls/cert.pem';
    private $auth;

    public function __construct($siteURL=null)
    {
        if (self::$cert_path !== false && !file_exists(self::$cert_path)) {
          self::$cert_path = GuzzleHttp\default_ca_bundle();
        }

        if (isset($siteURL)) {
            $this->auth = new \UNL\Templates\Auth\AuthCAS('2.0', 'shib.unl.edu', 443, '/idp/profile/cas', $siteURL, self::$cert_path, 'mediahub');
        } else {
            $this->auth = new \UNL\Templates\Auth\AuthCAS('2.0', 'shib.unl.edu', 443, '/idp/profile/cas', self::$cert_path, 'mediahub');
        }

        if (isset($_GET['logout'])) {
            $this->logout();
            exit();
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
        if ($this->auth->checkAuthentication()) {
            $this->setUser(UNL_MediaHub_User::getByUid($this->auth->getUserId()));
        }
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        if ($this->auth->isAuthenticated()) {
            return true;
        }

        return false;
    }

    public function login()
    {
        $this->auth->login();

        if (!$this->auth->getUserId()) {
            throw new UNL_MediaHub_RuntimeException('Unable to authenticate', 403);
        }

        $this->setUser(UNL_MediaHub_User::getByUid($this->auth->getUserId()));
    }

    public function logout()
    {
       $this->auth->logout(UNL_MediaHub_Controller::$url);
    }
    
    public function getUser()
    {
        if (!$this->user && $this->isLoggedIn()) {
            $this->setUser(UNL_MediaHub_User::getByUid($this->auth->getUserId()));
        }
        
        return parent::getUser();
    }
}
