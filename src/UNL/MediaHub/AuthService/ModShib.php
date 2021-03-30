<?php
class UNL_MediaHub_AuthService_ModShib extends UNL_MediaHub_AuthService_Interface
{
    private $auth;

    public function __construct($settingsInfo)
    {
        $this->auth =  new \UNL\Templates\Auth\AuthModShib($settingsInfo, 'mediahub');

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
        if ($this->auth->isAuthenticated()) {
          $this->setUser(UNL_MediaHub_User::getByUid($this->auth->getUserId()));
        }
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        // check if logged in
        return $this->auth->isAuthenticated();
    }

    public function login()
    {
        $this->auth->login();

        if (!$this->auth->getUserId()) {
          throw new RuntimeException('Unable to authenticate', 403);
        }

        $this->setUser(UNL_MediaHub_User::getByUid($this->auth->getUserId()));
    }

    public function logout()
    {
      $this->auth->logout();
    }

    public function getUser()
    {
      if (!$this->user && $this->isLoggedIn()) {
        $userID = $this->auth->getUserId();
        if (!empty($userID)) {
          $this->setUser(UNL_MediaHub_User::getByUid($userID));
        }
      }

      return parent::getUser();
    }
}
