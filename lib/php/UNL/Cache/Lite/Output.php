<?php

/**
* This class extends UNL_Cache_Lite and uses output buffering to get the data to cache.
*
* There are some examples in the 'docs/examples' file
* Technical choices are described in the 'docs/technical' file
*
* @package UNL_Cache_Lite
* @version $Id: Output.php 206076 2006-01-29 00:22:07Z fab $
* @author Fabien MARTY <fab@php.net>
*/

class UNL_Cache_Lite_Output extends UNL_Cache_Lite
{

    // --- Public methods ---

    /**
    * Constructor
    *
    * $options is an assoc. To have a look at availables options,
    * see the constructor of the UNL_Cache_Lite class in 'UNL_Cache_Lite.php'
    *
    * @param array $options options
    * @access public
    */
    function __construct($options = array(NULL))
    {
        $this->setOptions($options);
    }

    /**
    * Start the cache
    *
    * @param string $id cache id
    * @param string $group name of the cache group
    * @param boolean $doNotTestCacheValidity if set to true, the cache validity won't be tested
    * @return boolean true if the cache is hit (false else)
    * @access public
    */
    function start($id, $group = 'default', $doNotTestCacheValidity = false)
    {
        $data = $this->get($id, $group, $doNotTestCacheValidity);
        if ($data !== false) {
            echo($data);
            return true;
        }
        ob_start();
        ob_implicit_flush(false);
        return false;
    }

    /**
    * Stop the cache
    *
    * @access public
    */
    function end()
    {
        $data = ob_get_contents();
        ob_end_clean();
        $this->save($data, $this->_id, $this->_group);
        echo($data);
    }

}


?>
