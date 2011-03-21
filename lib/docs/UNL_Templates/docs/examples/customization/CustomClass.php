<?php

require_once 'UNL/Templates.php';

class CustomClass
{
    public $template;
    
    function __construct()
    {
        $this->template = UNL_Templates::factory('Fixed');
        $this->autoGenerate('Department of Mathematics', 'Math');
    }
    
    function autoGenerateBreadcrumbs($unitShortName, array $organization = array('name' => 'UNL', 'url' => 'http://www.unl.edu/'), $delimiter = ' | ')
    {
        $fileName             = array_shift(explode('.', array_pop(explode(DIRECTORY_SEPARATOR, htmlentities($_SERVER['SCRIPT_NAME'])))));
        $generatedBreadcrumbs = '';
        $generatedDocTitle    = '';
        
        $isIndexPage = preg_match('/index/', $fileName);
        
        $searchFor = array($_SERVER['DOCUMENT_ROOT'], '_');
        $replaceWith = array($unitShortName, ' ');
        
        $keys = explode(DIRECTORY_SEPARATOR, str_replace($searchFor, $replaceWith, getcwd()));
        $values = array();
        
        for ($i = count($keys)-1; $i >= 0; $i--) {
            array_push($values, str_replace($_SERVER['DOCUMENT_ROOT'], '', implode(DIRECTORY_SEPARATOR, explode(DIRECTORY_SEPARATOR, getcwd(), -$i)).DIRECTORY_SEPARATOR));
        }
        
        for ($i = 0; $i < count($keys)  - $isIndexPage ; $i++) {
            $generatedBreadcrumbs .= '<li><a href="'. $values[$i] .'">' . ucwords($keys[$i]) .' </a></li> '; 
            $generatedDocTitle    .= ucwords($keys[$i]) . $delimiter;
        }
    
        if ($isIndexPage) {
            $generatedBreadcrumbs .= '<li>'. ucwords(end($keys)) .'</li></ul>';
            $generatedDocTitle    .= ucwords(end($keys));
        } else {
            $generatedBreadcrumbs .= '<li>'. ucwords($fileName) .'</li></ul>';
            $generatedDocTitle    .= ucwords($fileName);
        }
        
        $doctitle    = '<title>' . $organization['name'] . $delimiter . $generatedDocTitle . '</title>';
        $breadcrumbs = '<ul><li class="first"><a href="'.$organization['url'].'">'.$organization['name'].'</a></li> ' . $generatedBreadcrumbs;
    
        $this->template->doctitle = $doctitle;
        $this->template->breadcrumbs = $breadcrumbs;
    }
    
    /**
     * This function finds an html file with the content of the body file and
     * inserts it into the template.
     *
     * @param string $unitName Name of the department/unit
     * 
     * @return void
     */
    function autoGenerateBody($unitName)
    {
        // The file that has the body is in the same dir with the same base file name.
        $bodyFile = array_shift(explode('.', array_pop(explode(DIRECTORY_SEPARATOR, htmlentities($_SERVER['SCRIPT_NAME']))))) . '.html';
    
        $maincontentarea_array = file($bodyFile);
        $maincontentarea       = implode(' ', $maincontentarea_array);
        $subhead               = preg_replace('/<!--\s*(.+)\s*-->/i', '$1', array_shift($maincontentarea_array));
    
        $titlegraphic = '<h1>' . $unitName . '</h1><h2>' . $subhead    . '</h2>';
    
        $this->template->titlegraphic    = $titlegraphic;
        $this->template->maincontentarea = $maincontentarea;
    }
    
    /**
     * Autogenerate the contents of a page.
     *
     * @param string $unitName      name of the unit/department
     * @param string $unitShortName abbreviation for the unit
     * @param array  $organization  organization heirarchy
     * @param string $delimiter     what separates files
     * 
     * @return void
     */
    function autoGenerate($unitName, $unitShortName, array $organization = array('name' => 'UNL', 'url' => 'http://www.unl.edu/'), $delimiter = ' | ')
    {
        $this->autoGenerateBreadcrumbs($unitShortName, $organization, $delimiter);
        $this->autoGenerateBody($unitName);
    }
    
    /**
     * renders a string representation of the template
     *
     * @return unknown
     */
    function __toString()
    {
        return $this->template->toHtml();
    }
}
?>