<?php

class UNL_MediaYak_Controller implements UNL_MediaYak_CacheableInterface, UNL_MediaYak_PostRunReplacements
{
    protected static $auth;

    public $options;
    
    public $template = 'Fixed';
    
    public $output;
    
    public static $url;
    
    public static $thumbnail_generator;
    
    static protected $replacements;
    
    function __construct($dsn)
    {
        include_once 'Doctrine/lib/Doctrine.php';

        spl_autoload_register(array('Doctrine', 'autoload'));
        
        Doctrine_Manager::getInstance()->setAttribute('model_loading', 'conservative');
        Doctrine::loadModels(dirname(dirname(dirname(__FILE__))).'/UNL/MediaYak/Media');
        Doctrine_Manager::connection($dsn);
        
        $this->options = $_GET + array('view'   => null,
                                       'format' => null,
                                       );
    }
    
    static function isLoggedIn()
    {
        include_once 'UNL/Auth.php';
        self::$auth = UNL_Auth::factory('CAS');
        if (self::$auth->isLoggedIn()) {
            return true;
        }
        
        return false;
    }
    
    function getCacheKey()
    {
        return false;
    }
    
    function preRun()
    {
        switch ($this->options['format']) {
            case 'xml':
                header('Content-type: text/xml');
                UNL_MediaYak_OutputController::setOutputTemplate('UNL_MediaYak_Controller','ControllerXML');    
            default:
                break;
        }
        return true;
    }
    
    function run()
    {
        if (self::isLoggedIn()) {
            // We're golden.
        }
        
        switch ($this->options['view'])
        {
            case 'media':
                $media = $this->findRequestedMedia($this->options);
                $this->showMedia($media);
                break;
            case 'search':
            default:
                $this->showLatestMedia();
        }
    }
    
    function findRequestedMedia($options)
    {
        $media = false;
        if (isset($options['id'])) {
            $media = Doctrine::getTable('UNL_MediaYak_Media')->find($options['id']);
        }
        
        if (isset($_POST, $_POST['comment'])
            && self::isLoggedIn()) {
            $comment = new UNL_MediaYak_Media_Comment();
            $data = array('uid'      => self::$auth->getUID(),
                          'media_id' => $media->id,
                          'comment'  => $_POST['comment']);
            $comment->fromArray($data);
            $comment->save();
        }
        
        
        if ($media) {
            return $media;
        }
        /*
        if (isset($options['y'],
                  $options['m'],
                  $options['d'],
                  $options['headline'])) {
            $date     = $options['y'].'-'.$options['m'].'-'.$options['d'];
            $headline = urldecode($options['headline']);
            $filter   = new UNL_MediaYak_MediaList_Filter_ByDateAndHeadline($date, $headline);
            $query    = new Doctrine_Query();
            $query->from('UNL_MediaYak_Media m');
            $filter->apply($query);
            $results = $query->execute();
            if (count($results)) {
                return $results[0];
            }
        }
        */
        
        throw new Exception('Cannot determine the media to display.');
    }
    
    /**
     * Called after run - with all output contents.
     *
     * @param string $me
     * 
     * @return string
     */
    function postRun($me)
    {
        $scanned = new UNL_Templates_Scanner($me);
        
        if (isset(self::$replacements['title'])) {
            $me = str_replace($scanned->doctitle,
                              '<title>'.self::$replacements['title'].'</title>',
                              $me);
        }
        
        if (isset(self::$replacements['head'])) {
            $me = str_replace('</head>', self::$replacements['head'].'</head>', $me);
        }

        if (isset(self::$replacements['breadcrumbs'])) {
            $me = str_replace($scanned->breadcrumbs, self::$replacements['breadcrumbs'], $me);
        }
        
        return $me;
    }
    
    static function setReplacementData($field, $data)
    {
        switch ($field) {
            case 'title':
            case 'head':
            case 'breadcrumbs':
                self::$replacements[$field] = $data;
                break;
        }
    }
    
    function getURL($mixed = null, $additional_params = array())
    {
        $params = array();
        $url = UNL_MediaYak_Controller::$url;
        
        if (is_object($mixed)) {
            switch (get_class($mixed)) {
                case 'UNL_MediaYak_Media':
                    //$params['view'] = 'release';
                    //$params['id']   = $mixed->id;
                    //$url .= date('Y/m/d/', strtotime($mixed->datecreated));
                    $url .= 'media/'.$mixed->id;
                    break;
                case 'UNL_MediaYak_MediaList':
                    $url = $mixed->getURL();
                default:
                    
            }
        }
        
        $params = array_merge($params, $additional_params);
        
        $url .= '?';
        
        foreach ($params as $option=>$value) {
            if (!empty($value)) {
                $url .= "&amp;$option=$value";
            }
        }
        
        return trim($url, '?;&amp=');
    }
    
    function showLatestMedia()
    {
        $filter = null;
        if (isset($this->options['q'])) {
            $filter = new UNL_MediaYak_MediaList_Filter_TextSearch($this->options['q']);
        }
        
        $this->output = new UNL_MediaYak_MediaList($filter);
    }
    
    function showMedia(UNL_MediaYak_Media $media)
    {
        $this->output   = $media;
        if (!$this->output) {
            header('HTTP/1.0 404 Not Found');
            throw new Exception('Could not find that news release.');
        }
        $this->output->loadReference('UNL_MediaYak_Media_Comment');
    }
}

?>