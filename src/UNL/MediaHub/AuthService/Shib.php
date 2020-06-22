<?php
class UNL_MediaHub_AuthService_Shib extends UNL_MediaHub_AuthService_Interface
{
    // likely not a thing for shib
    //public static $cert_path = '/etc/pki/tls/cert.pem';
    private $auth;

    public function __construct()
    {
        $this->auth = new \SimpleSAML\Auth\Simple('sp-mediahub');

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
        // TODO verify if want to check this for shib (likley do)
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
        // TODO: verify shib authentication
        //if (\phpCAS::checkAuthentication()) {
        //    $this->setUser(UNL_MediaHub_User::getByUid(\phpCAS::getUser()));
        //}
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        // check if logged in
        if ($this->auth->isAuthenticated()) {
         return true;
        }

        return false;
    }

    public function login()
    {
        $this->auth->login();
        \SimpleSAML\Session::getSessionFromRequest()->cleanup();

        // Verify logged in, throw error if not
        //if (???) {
        //    throw new RuntimeException('Unable to authenticate', 403);
        //}

        //$this->setUser();
    }

    public function logout()
    {
        //TODO logout of shib
        //\phpCAS::logoutWithRedirectService(UNL_MediaHub_Controller::$url);
    }

    public function getUser()
    {
        if (!$this->user && $this->isLoggedIn()) {
            //$this->setUser();
        }

        return parent::getUser();
    }
}
