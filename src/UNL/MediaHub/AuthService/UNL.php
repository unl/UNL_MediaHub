<?php
class UNL_MediaHub_AuthService_UNL extends UNL_MediaHub_AuthService_Interface
{
    protected $auto_auth_models = array(
        'UNL_MediaHub_MediaList',
        'UNL_MediaHub_DefaultHomepage',
        'UNL_MediaHub_FeedList',
        'UNL_MediaHub_FeedAndMedia',
        'media',
    );
    
    public static $cert_path = '/etc/pki/tls/cert.pem';

    public function __construct()
    {
        if (!\phpCAS::isInitialized()) {
            session_name('mediahub');
            
            \phpCAS::client(CAS_VERSION_2_0, 'login.unl.edu', 443, 'cas');
            
            if (!file_exists(self::$cert_path)) {
                self::$cert_path = GuzzleHttp\default_ca_bundle();
            }
            
            \phpCAS::setCasServerCACert(self::$cert_path);
        }
        
        \phpCAS::handleLogoutRequests(false);
        
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
        if (\phpCAS::checkAuthentication()) {
            $this->setUser(UNL_MediaHub_User::getByUid(\phpCAS::getUser()));
        }
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        if (\phpCAS::isAuthenticated()) {
            return true;
        }

        return false;
    }

    public function login()
    {
        \phpCAS::forceAuthentication();


        if (!\phpCAS::getUser()) {
            throw new RuntimeException('Unable to authenticate', 403);
        }

        $this->setUser(UNL_MediaHub_User::getByUid(\phpCAS::getUser()));
    }

    public function logout()
    {
        \phpCAS::logoutWithRedirectService(UNL_MediaHub_Controller::$url);
    }
    
    public function getUser()
    {
        if (!$this->user && $this->isLoggedIn()) {
            $this->setUser(UNL_MediaHub_User::getByUid(\phpCAS::getUser()));
        }
        
        return parent::getUser();
    }
}
