<?php
class UNL_MediaHub_BaseController
{
    public static $options = array();
    
    /**
     * Get a cacheable version of the media player (we need to inject the controller options for this)
     *
     * @param $media
     * @return UNL_MediaHub_MediaPlayer
     */
    public function getMediaPlayer($media)
    {
        return new UNL_MediaHub_MediaPlayer($media, $this->options);
    }

    public static function addNotice(UNL_MediaHub_Notice $notice)
    {
        if (!isset($_SESSION['notices'])) {
            $_SESSION['notices'] = array();
        }

        $_SESSION['notices'][] = $notice;
    }

    /**
     * Wrapper function to help with CSRF tokens
     *
     * @return \Slim\Csrf\Guard
     */
    public function getCSRFHelper()
    {
        static $csrf;

        if (!$csrf) {
            $null = null;
            // Use persistent tokens due to AJAX functionality
            $csrf = new \Slim\Csrf\Guard('csrf', $null, null, 200, 16, true);
            $csrf->validateStorage();
            $csrf->generateToken();
        }

        return $csrf;
    }

    /**
     * Validate a POST request for CSRF
     *
     * @return bool
     */
    public function validateCSRF()
    {
        $csrf = $this->getCSRFHelper();

        if (!isset($_POST[$csrf->getTokenNameKey()])) {
            return false;
        }

        if (!isset($_POST[$csrf->getTokenValueKey()])) {
            return false;
        }

        $name = $_POST[$csrf->getTokenNameKey()];
        $value = $_POST[$csrf->getTokenValueKey()];

        return $csrf->validateToken($name, $value);
    }
}