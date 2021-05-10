<?php
class UNL_MediaHub_AuthService
{
    public static $provider;
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

        if (empty($provider)) {
            // use config auth service provider or default if not passed in
            if (static::$provider instanceof UNL_MediaHub_AuthService_Interface) {
                $provider = static::$provider;
            } else {
                $provider = new UNL_MediaHub_AuthService_Shib();
            }
        }

        self::$instance = $provider;
        
        return self::$instance;
    }
}