<?php
/**
 * Base class for Version 3 (2009) template files.
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

/**
 * Base class for Version 3 (2009) template files.
 * 
 * @category  Templates
 * @package   UNL_Templates
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2009 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/
 */
class UNL_Templates_Version3 implements UNL_Templates_Version
{ 
    function getConfig()
    {
        return array('class_location' => 'UNL/Templates/Version3/',
                     'class_prefix'   => 'UNL_Templates_Version3_');
    }
    
    function getTemplate($template)
    {
        if (!file_exists(UNL_Templates::$options['templatedependentspath'].'/wdn/templates_3.0')) {
            UNL_Templates::debug('ERROR You should have a local copy of wdn/templates_3.0!'
                                 . ' Overriding your specified template to use absolute references' ,
                                 'getTemplate', 1);
            $template = 'Absolute.tpl';
        }

        // Always try and retrieve the latest
        if (!(UNL_Templates::getCachingService() instanceof UNL_Templates_CachingService_Null)
            && $tpl = file_get_contents('http://pear.unl.edu/UNL/Templates/server.php?version=3&template='.$template)) {
            return $tpl;
        }

        if (file_exists(UNL_Templates::getDataDir().'/tpl_cache/Version3/'.$template)) {
            return file_get_contents(UNL_Templates::getDataDir().'/tpl_cache/Version3/'.$template);
        }

        throw new Exception('Could not get the template file!');
    }
    
    function makeIncludeReplacements($html)
    {
        UNL_Templates::debug('Now making template include replacements.',
                     'makeIncludeReplacements', 3);
        $includes = array();
        preg_match_all('<!--#include virtual="(/wdn/templates_3.0/[A-Za-z0-9\.\/]+)" -->',
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
