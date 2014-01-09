<?php
/**
 * Handles scanning a dwt file for regions and rendering.
 *
 * PHP version 5
 *
 * @category  Templates
 * @package   UNL_DWT
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @created   01/18/2006
 * @copyright 2008 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/package/UNL_DWT
 */
require_once 'UNL/DWT.php';
require_once 'UNL/DWT/Region.php';

/**
 * Will scan a dreamweaver templated file for regions and other relevant info.
 *
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @created   01/18/2006
 * @copyright 2008 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/package/UNL_DWT
 */
class UNL_DWT_Scanner extends UNL_DWT
{
    protected $_regions;

    /**
     * The contents of the .dwt file you wish to scan.
     *
     * @param string $dwt Source of the .dwt file
     */
    function __construct($dwt)
    {
        $this->__template = $dwt;
        $this->scanRegions($dwt);
    }

    /**
     * Return the template markup
     *
     * @return string
     */
    function getTemplateFile()
    {
        return $this->__template;
    }

    function scanRegions($dwt)
    {
        $this->_regions[] = array();

        $dwt = str_replace("\r", "\n", $dwt);
        $dwt = preg_replace("/(\<\!-- InstanceBeginEditable name=\"([A-Za-z0-9]*)\" -->)/i", "\n\\0\n", $dwt);
        $dwt = preg_replace("/(\<\!-- TemplateBeginEditable name=\"([A-Za-z0-9]*)\" -->)/i", "\n\\0\n", $dwt);
        $dwt = preg_replace("/\<\!-- InstanceEndEditable -->/", "\n\\0\n", $dwt);
        $dwt = preg_replace("/\<\!-- TemplateEndEditable -->/", "\n\\0\n", $dwt);
        $dwt = explode("\n", $dwt);

        $newRegion = false;
        $region    = new UNL_DWT_Region();
        foreach ($dwt as $key=>$fileregion) {
            $matches = array();
            if (preg_match("/\<\!-- InstanceBeginEditable name=\"([A-Za-z0-9]*)\" -->/i", $fileregion, $matches)
                || preg_match("/\<\!-- TemplateBeginEditable name=\"([A-Za-z0-9]*)\" -->/i", $fileregion, $matches)) {
                if ($newRegion == true) {
                    // Found a new nested region.
                    // Remove the previous one.
                    $dwt[$region->line] = str_replace(array("<!--"." InstanceBeginEditable name=\"{$region->name}\" -->"), '', $dwt[$region->line]);
                }
                $newRegion     = true;
                $region        = new UNL_DWT_Region();
                $region->name  = $matches[1];
                $region->line  = $key;
                $region->value = "";
            } elseif ((preg_match("/\<\!-- InstanceEndEditable -->/i", $fileregion, $matches) || preg_match("/\<\!-- TemplateEndEditable -->/", $fileregion, $matches))) {
                // Region is closing.
                if ($newRegion===true) {
                    $region->value = trim($region->value);
                    if (strpos($region->value, "@@(\" \")@@") === false) {
                        $this->_regions[$region->name] = $region;
                    } else {
                        // Editable Region tags must be removed within .tpl
                        unset($dwt[$region->line], $dwt[$key]);
                    }
                    $newRegion = false;
                } else {
                    // Remove the nested region closing tag.
                    $dwt[$key] = str_replace("<!--"." InstanceEndEditable -->", '', $fileregion);
                }
            } else {
                if ($newRegion===true) {
                    // Add the value of this region.
                    $region->value .= trim($fileregion).PHP_EOL;
                }
            }
        }
    }

    /**
     * returns the region object
     *
     * @param string $region
     *
     * @return UNL_DWT_Region
     */
    public function getRegion($region)
    {
        if (isset($this->_regions[$region])) {
            return $this->_regions[$region];
        }
        return null;
    }

    /**
     * returns array of all the regions found
     *
     * @return array(UNL_DWT_Region)
     */
    public function getRegions()
    {
        return $this->_regions;
    }

    public function __isset($region)
    {
        return isset($this->_regions[$region]);
    }

    public function __get($region)
    {
        if (isset($this->_regions[$region])) {
            return $this->_regions[$region]->value;
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property: ' . $region .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }

    /**
     * Allow directly rendering
     *
     * @return string
     */
    function __toString()
    {
        return $this->toHtml();
    }

}
