<?php
class UNL_MediaHub_Developers_Media
{
    public $title       = "Media Instance";
    
    public $uri         = "media/{id}";
    
    public $exampleURI  = "media/1";
    
    public $note        = "All xml extra xml elements for media will be outputed to JSON with a '(namespace)_(key)':'(value)' pattern.  These elements
                          include, but are not limited to: geo infomration, itunes details, media details, and boxee information.  These values will only display if they are set.";
    
    public $properties  = array(
                                array("id", "int: A numerical id for the media.", true, false),
                                array("url", "URL: A url to the actual media file.", true, false),
                                array("link", "URL: The url to the media file on Media Hub.", true, true),
                                array("title", "Text: The title of the media.", true, true),
                                array("description", "Text: The descripton of the media.", true, true),
                                array("length", "Int: The size of the media file in bytes.", true, false),
                                array("image", "URL: A url to the image of the midea.", true, false),
                                array("type", "TEXT: The type of media.", true, false),
                                array("author", "Text: The author of the media.", true, false),
                                array("pubDate", "Date: The date the media was published. YYYY-MM-DD HH:MM:SS.", true, true),
                                array("dateupdated", "Date: The date the media was last updated. YYYY-MM-DD HH:MM:SS.", true, false),
                                array("lastBuildDate","Date: The date and time that the feed was last built." , false, true),
                                array("docs","URL: Link to RSS specification", false, true),
                                array("generator", "    Text: Name and version of the generator used to generate the channel.", false, true),
                                array("managingEditor", "Text: Details about the managing Editor.", false, true),
                                array("webMaster", "Text: Details about the webmaster.", false, true),
                                array("ttl","Int: The maximum number of minutes the chanel has to live before referesing from the source.", false, true)
                                );
                                
    public $formats     = array("json", "xml", "partial");
    
    function __construct()
    {
        $this->uri = UNL_MediaHub_Controller::$url . $this->uri;
        $this->exampleURI  = UNL_MediaHub_Controller::$url . $this->exampleURI;
    }
}