<?php
class UNL_MediaYak_Developers_Search
{
    public $title       = "Search";
    
    public $uri         = "search/{query}";
    
    public $exampleURI  = "search/idk";
    
    public $properties  = array(
                                array("{results}", "A list of the <a href='?resource=Media'> media instances </a> that were found for the query tag. ", true, true),
                                );
                                
    public $formats     = array("json", "xml", "partial");
    
    function __construct()
    {
        $this->uri = UNL_MediaYak_Controller::$url . $this->uri;
        $this->exampleURI  = UNL_MediaYak_Controller::$url . $this->exampleURI;
    }
}