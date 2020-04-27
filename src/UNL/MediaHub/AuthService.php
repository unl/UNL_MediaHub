<?php
class UNL_MediaHub_AuthService
{
    protected static $instance = false;
    
    protected function __construct() {}

    /**
     * @param UNL_MediaHub_AuthService_Interface $provider
     * @return bool|UNL_MediaHub_AuthService_Interface
     */
    public static function getInstance(UNL_MediaHub_AuthService_Interface $provider = NULL)
    {
        if (self::$instance) {
            return self::$instance;
        }
        
        if (!$provider) {
            $provider = new UNL_MediaHub_AuthService_UNL();
        }

        self::$instance = $provider;
        
        return self::$instance;
    }
}