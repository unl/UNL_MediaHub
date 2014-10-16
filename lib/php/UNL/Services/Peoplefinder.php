<?php
/**
 * Package which connects to the peoplefinder services to get information about UNL people.
 *
 * 
 * @author Brett Bieber
 * @package UNL_Services_Peoplefinder
 */

/**
 * This is the basic class for utilizing the UNL Peoplefinder service which can
 * give you various pieces of information about a given uid (uniue user id).
 * 
 * @package UNL_Services_Peoplefinder
 */
abstract class UNL_Services_Peoplefinder
{
    
    /**
     * returns the name for a given uid
     *
     * @param string $uid
     * @return string|false
     */
    public static function getFullName($uid)
    {
        if ($vcard = UNL_Services_Peoplefinder::getVCard($uid)) {
            $matches = array();
            preg_match_all('/FN:(.*)/',$vcard, $matches);
            if (isset($matches[1][0]) && $matches[1][0] != ' ') {
                return $matches[1][0];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    /**
     * returns the email address for the given uid
     *
     * @param string $uid
     * @return string|false
     */
    public static function getEmail($uid)
    {
        if ($hcard = UNL_Services_Peoplefinder::getHCard($uid)) {
            $matches = array();
            preg_match_all('/mailto:([^\'\"]*)/',$hcard, $matches);
            if (isset($matches[1][0])) {
                return $matches[1][0];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    /**
     * Gets an hcard for the uid given.
     *
     * @param string $uid
     * @return string|false
     */
    public static function getHCard($uid)
    {
        if ($hcard = file_get_contents('http://peoplefinder.unl.edu/hcards/'.$uid)) {
            return $hcard;
        } else {
            return false;
        }
    }
    
    /**
     * Gets a vcard for the given uid.
     *
     * @param string $uid
     * @return string|false
     */
    public static function getVCard($uid)
    {
        if ($vcard = file_get_contents('http://peoplefinder.unl.edu/vcards/'.$uid)) {
            return $vcard;
        } else {
            return false;
        }
    }
}

?>