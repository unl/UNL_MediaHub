<?php
class UNL_MediaHub_Developers_Channel
{
    public $title       = "Channel";
    
    public $uri         = "channels/{id}";
    
    public $exampleURI  = "channels/1";
    
    public $properties  = array(
                                array("id", "int: A numerical id for the media.", true, false),
                                array("link", "URL: The url to the media file on Media Hub.", true, true),
                                array("title", "Text: The title of the media.", true, true),
                                array("description", "Text: The descripton of the media.", true, true),
                                array("image", "URL: A url to the image of the midea.", true, false),
                                array("uidcreated", "Text: The UID of the user who created the channel", true, false),
                                array("language", "Text: The name of the language used in the channel.", false, true),
                                array("pubDate", "Date: The date the media was published. YYYY-MM-DD HH:MM:SS.", true, true),
                                array("dateupdated", "Date: The date the media was last updated. YYYY-MM-DD HH:MM:SS.", true, false),
                                array("lastBuildDate","Date: The date and time that the feed was last built." , false, true),
                                array("docs","URL: Link to RSS specification", false, true),
                                array("generator", "    Text: Name and version of the generator used to generate the channel.", false, true),
                                array("managingEditor", "Text: Details about the managing Editor.", false, true),
                                array("webMaster", "Text: Details about the webmaster.", false, true),
                                array("ttl","Int: The maximum number of minutes the chanel has to live before referesing from the source.", false, true),
                                array("{media}","A list of the <a href='?resource=media'>media instances</a> in the channel.", true, true)
                                );
                                
    public $formats     = array("json", "xml", "partial");
    
    function __construct()
    {
        $this->uri = UNL_MediaHub_Controller::$url . $this->uri;
        $this->exampleURI  = UNL_MediaHub_Controller::$url . $this->exampleURI;
    }
}