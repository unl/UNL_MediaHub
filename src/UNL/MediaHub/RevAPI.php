<?php
class UNL_MediaHub_RevAPI
{
    public static $client_api_key = false;
    public static $user_api_key = false;
    public static $host = false;
    public static $http_config = array();

    /**
     * Number of seconds until timeout for requests.
     *
     * @var int
     */
    protected $timeout = 2;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    private function __construct()
    {
        
    }

    /**
     * @return bool|\RevAPI\Rev
     */
    public static function getRevClient()
    {
        if (self::$client_api_key == false
        || self::$user_api_key == false
        || self::$host == false
        ) {
            // it isn't gonna work.
            return false;
        }
        
        
        if (!self::$host) {
            $host = \RevAPI\Rev::SANDBOX_HOST;
        } else {
            $host = self::$host;
        }
        
        $rev = new \RevAPI\Rev(self::$client_api_key, self::$user_api_key, $host, self::$http_config);
        
        return $rev;
    }
}