<?php
/**
 * Base class for version 2 (2006) of the template files.
 * 
 * PHP version 5
 *  
 * @category  Templates
 * @package   UNL_Templates
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @author    Ned Hummel <nhummel2@unl.edu>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/
 */
require_once 'UNL/Templates/Version.php';

class UNL_Templates_Version2 implements UNL_Templates_Version
{ 
    function getConfig()
    {
        return array('class_location' => 'UNL/Templates/Version2/',
                     'class_prefix'   => 'UNL_Templates_Version2_');
    }
    
    function getTemplate($template)
    {
        // Set a timeout for the HTTP download of the template file
        $http_context = stream_context_create(array('http' => array('timeout' => UNL_Templates::$options['timeout'])));

        // Always try and retrieve the latest
        if (!(UNL_Templates::getCachingService() instanceof UNL_Templates_CachingService_Null)
            && $tpl = file_get_contents('http://pear.unl.edu/UNL/Templates/server.php?version=2&template='.$template, false, $http_context)) {
            return $tpl;
        }

        if (file_exists(UNL_Templates::getDataDir().'/tpl_cache/Version2/'.$template)) {
            return file_get_contents(UNL_Templates::getDataDir().'/tpl_cache/Version2/'.$template);
        }

        throw new Exception('Could not get the template file!');
    }
    
    function makeIncludeReplacements($html)
    {
        UNL_Templates::debug('Now making template include replacements.',
                     'makeIncludeReplacements', 3);
        $includes = array();
        preg_match_all('<!--#include virtual="(/ucomm/templatedependents/[A-Za-z0-9\.\/]+)" -->',
                        $html, $includes);
        UNL_Templates::debug(print_r($includes, true), 'makeIncludeReplacements', 3);
        foreach ($includes[1] as $include) {
            UNL_Templates::debug('Replacing '.$include, 'makeIncludeReplacements', 3);
            $file = UNL_Templates::$options['templatedependentspath'].$include;
            if (!file_exists($file)) {
                UNL_Templates::debug('File does not exist:'.$file,
                             'makeIncludeReplacements', 3);
                $file = 'http://www.unl.edu'.$include;
            }
            $html = str_replace('<!--#include virtual="'.$include.'" -->',
                                 file_get_contents($file), $html);
        }
        return $html;
    }
}
