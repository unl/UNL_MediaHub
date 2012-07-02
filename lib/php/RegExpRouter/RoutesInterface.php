<?php
/**
 * RegExpRouter RoutesInterface
 * 
 * This file cantins the routes interface class that can be
 * optionally used for scanning routes.
 * 
 * PHP Version 5
 * 
 * LICENSE http://www.opensource.org/licenses/mit-license.php
 * 
 * @category Router
 * @package  RegExpRouter
 * @author   Michael Fairchild <mfairchild365@gmail.com>
 * @author   Brett Bieber <brett.bieber@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  GIT: <git_id>
 * @link     #
 */
namespace RegExpRouter;


/**
 * RoutesInterface
 * 
 * This class is a routes interface.  All Routes class must
 * implement this interface to work properly.
 *
 * @category Router
 * @package  RegExpRouter
 * @author   Michael Fairchild <mfairchild365@gmail.com>
 * @author   Brett Bieber <brett.bieber@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     #
 */
abstract class RoutesInterface
{
    /**
     * All of the Post POST for this model.
     * 
     * @return array an associative array of routes
     */
    abstract public static function getPostRoutes();
    
    /**
     * All of the GET Routes for this model.
     * 
     * @return array an associative array of routes
     */
    abstract public static function getGetRoutes();
    
    /**
     * All of the DELETE Routes for this model.
     * 
     * @return array an associative array of routes
     */
    abstract public static function getDeleteRoutes();
    
    /**
     * All of the PUT Routes for this model.
     * 
     * @return array an associative array of routes
     */
    abstract public static function getPutRoutes();
    
    /**
     * Gathers all of the Routes for this object.
     * It then adds the called class's parent's namespace to all of the routes.
     * The namespace is added here to make development faster, and to ensure
     * that all routes belong to only one model.
     * Finally it returns the routs with the added namespace.
     * 
     * @return array $routes an associative array of routes
     */
    public static function getRoutes()
    {
        $class     = get_called_class();
        $routes    = array();
        $namespace = substr($class, 0, strlen($class)-6);
        $routes += call_user_func($class . "::getPostRoutes");
        $routes += call_user_func($class . "::getGetRoutes");
        $routes += call_user_func($class . "::getDeleteRoutes");
        $routes += call_user_func($class . "::getPutRoutes");
        
        return self::addNamesapces($namespace, $routes);
    }
    
    /**
     * Adds a namespace to the routes's model class.
     * 
     * @param string $nameSpace the namespace to add to the routes
     * @param array  $routes    the routes to be modified
     * 
     * @return array $newRoutes the modified routes
     */
    protected static function addNamesapces($nameSpace, array $routes)
    {
        $newRoutes = array();
        
        foreach ($routes as $regex=>$route) {
            $route = $nameSpace.$route;
            $newRoutes[$regex] = $route;
        }
        
        return $newRoutes;
    }
}