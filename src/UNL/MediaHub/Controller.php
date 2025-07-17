<?php
/**
 * Controller for the public frontend to the MediaHub system.
 */
class UNL_MediaHub_Controller
    extends UNL_MediaHub_BaseController
    implements UNL_MediaHub_CacheableInterface, UNL_MediaHub_PostRunReplacements
{
    /**
     * Array of options
     *
     * @var array
     */
    public $options = array(
        'model'  => false,
        'format' => 'html',
    );

    /** 
     * MediaHub copy of $options['model']
     * 
     * @var string
    */
    public static $model;

    /**
     * Mediahub app name to use.
     *
     * @var string
     */
    public static $appName;

    /**
     * Mediahub themePath to use.
     *
     * @var string
     */
    public static $themePath;

    /**
     * Mediahub custom theme template to use.
     *
     * @var string
     */
    public static $customThemeTemplate;

    /**
     * UNL template style to use.
     *
     * @var string
     */
    public static $template;

    /**
     * UNL template version to use.
     *
     * @var string
     */
    public static $templateVersion;

    /**
     * Any output prepared by this controller will go here. This could be
     * any type of data, string, array or object.
     *
     * @var mixed
     */
    public $output;

    /**
     * URL to this controller.
     *
     * @var string
     */
    public static $url;

    /**
     * URL to a thumbnail generator for media files.
     *
     * @var string
     */
    public static $thumbnail_generator;

    static protected $replacements;

    /**
     * The current embed version to serve
     * 
     * @var int
     */
    public static $current_embed_version = 3;

    /**
     * The date on which captions are determined to be required.
     * 
     * @var false|string - date in mysql format
     */
    public static $caption_requirement_date = false;

    protected $view_map = array(
        'search'  => 'UNL_MediaHub_MediaList',
        'tags'    => 'UNL_MediaHub_MediaList',
        'default' => 'UNL_MediaHub_DefaultHomepage',
        'feeds'   => 'UNL_MediaHub_FeedList',
        'feed'    => 'UNL_MediaHub_FeedAndMedia',
        'dev'     => 'UNL_MediaHub_Developers',
        'live'    => 'UNL_MediaHub_Feed_LiveStream',
        );

    /**
     * Backend media system.
     *
     * @var UNL_MediaHub
     */
    protected $mediahub;

    public static $usedMediaNameSpaces = array(
                       'UNL_MediaHub_Feed_Media_NamespacedElements_itunesu',
                       'UNL_MediaHub_Feed_Media_NamespacedElements_itunes',
                       'UNL_MediaHub_Feed_Media_NamespacedElements_media',
                       'UNL_MediaHub_Feed_Media_NamespacedElements_boxee',
                       'UNL_MediaHub_Feed_Media_NamespacedElements_geo',
                       'UNL_MediaHub_Feed_Media_NamespacedElements_mediahub');

    /**
     * Whether or not videos should be auto-muxed
     * 
     * @var bool
     */
    public static $auto_mux = true;
    
    public static $max_upload_mb = '1024';
    
    /**
     * Construct a new controller.
     *
     * @param string $dsn Database connection string
     */
    function __construct($options)
    {
        // Set up database
        $this->mediahub = new UNL_MediaHub();

        // Initialize default options
        $this->options = $options + $this->options;

        // set this for later use
        UNL_MediaHub_Controller::$model = $this->options['model'];
        
        if ($this->options['model'] == 'media_embed') {
            $this->options['format'] = 'js';
        }

        if ($this->options['model'] == 'media_oembed' && $this->options['format'] == 'html') {
            $this->options['format'] = 'json';
        }

        // Do not auto login for iframe requests
        if ($this->options['format'] != 'iframe') {
            UNL_MediaHub_AuthService::getInstance()->autoLogin($this->options['model']);
        }

        UNL_MediaHub_Feed_Media_NamespacedElements_mediahub::$uri = UNL_MediaHub_Controller::$url . "schema/mediahub.xsd";
    }
    
    
    public static function getNamespaceDefinationString()
    {
        $namespaces = "";
        foreach (UNL_MediaHub_Controller::$usedMediaNameSpaces as $class) {
            $class = new ReflectionClass($class);
            $namespaces .= "xmlns:" . $class->getStaticPropertyValue('xmlns') . "='" . $class->getStaticPropertyValue('uri') . "' ";
        }

        return $namespaces;
    }

    /**
     * Get the cache key for the data prepared by the controller
     *
     * @return string|false
     */
    function getCacheKey()
    {
        return false;
    }

    /**
     * function called before output, cached or otherwise is sent.
     *
     * @return bool
     */
    function preRun($cached)
    {
        if ($this->options['model'] == 'feed_image'
            || $this->options['model'] == 'media_embed'
            || $this->options['model'] == 'media_oembed'
            || $this->options['model'] == 'media_vtt'
            || $this->options['model'] == 'media_srt') {
            UNL_MediaHub_OutputController::setOutputTemplate('UNL_MediaHub_Controller', 'ControllerPartial');
        }
        
        // Send headers for CORS support so calendar bits can be pulled remotely
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: X-Requested-With');
        if (isset($_SERVER['REQUEST_METHOD'])
            && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            // short circuit execution for CORS OPTIONS reqeusts
            exit();
        }
        
        switch ($this->options['format']) {
        case 'xml':
        case 'mosaic-xml':
        case 'rss':
            // Send XML content-type headers, and assign XML output template.
            header('Content-type: text/xml');
            break;
        case 'partial':
            UNL_MediaHub_OutputController::setOutputTemplate('UNL_MediaHub_Controller', 'ControllerPartial');
            break;
        case 'json':
            header('Content-type: application/json');
            break;
        case 'srt':
            header('Content-type: text/srt');
            break;
        case 'vtt':
            header('Content-type: text/vtt');
            break;
        case 'js':
            header('Content-type: text/javascript');
            break;
        case 'iframe':
            if ('media' === $this->options['model']) {
                header_remove('X-Frame-Options');
            }
            break;
        default:
            break;
        }
        
        return true;
    }

    /**
     * Main hub for the controller.
     *
     * This will be called when cached output cannot be found.
     *
     * @return void
     */
    function run()
    {
        try {
            if (!empty($_POST)) {
                $this->handlePost($_POST);
            }

            if (!isset($this->options['model']) || false === $this->options['model']) {
                throw new Exception('Un-registered view', 404);
            }

            switch ($this->options['model']) {
            case 'media':
                $media = $this->findRequestedMedia($this->options);
                
                $transcoding_job = $media->getMostRecentTranscodingJob();
                
                if ($transcoding_job && $transcoding_job->isPending()) {
                    throw new Exception('This media is being optimized. Please try back later.', 200);
                }

                if ($transcoding_job && $transcoding_job->isError()) {
                    throw new Exception('There was an error optimizing this media.', 500);
                }

                // Auto login private and protected iframe media
                if ($this->options['format'] === 'iframe' && !$media->canView()) {
                    // attempt autologin but will likely fail in iframe;
                    UNL_MediaHub_AuthService::getInstance()->autoLogin($this->options['model']);
                    if (!$media->canView()) {
                        $this->displayIframeAccessDeniedMessage($media);
                    }
                }
                $this->canView($media);
                
                if (!$media->meetsCaptionRequirement()) {
                    $notice = new UNL_MediaHub_Notice(
                        'Notice',
                        'This media will not be published until captions are provided.',
                        UNL_MediaHub_Notice::TYPE_INFO
                    );
                    $notice->addLink($media->getEditCaptionsURL(), 'Add Captions Now');
                    UNL_MediaHub_Manager::addNotice($notice);
                }
                
                $this->output[] = $media;
                break;
            case 'feed_image':
                if (isset($this->options['feed_id'])) {
                    $this->output[] = UNL_MediaHub_Feed_Image::getById($this->options['feed_id']);
                } else {
                    $this->output[] = UNL_MediaHub_Feed_Image::getByTitle($this->options['title']);
                }
                break;
            case 'media_vtt':
                $this->output[] = new UNL_MediaHub_Media_VideoTextTrack($this->options, UNL_MediaHub_MediaTextTrackFile::FORMAT_VTT);
                break;
            case 'media_srt':
                $this->output[] = new UNL_MediaHub_Media_VideoTextTrack($this->options, UNL_MediaHub_MediaTextTrackFile::FORMAT_SRT);
                break;
            case 'media_image':
                $image = new UNL_MediaHub_Media_Image($this->options);
                $file = $image->getThumbnail();
                header('Content-Type: image/jpeg');
                readfile($file);
                exit();
            case 'media_embed':
                $id = null;
                if (isset($this->options['id'])) {
                    $id = $this->options['id'];
                }
                $version = 1;
                if (isset($this->options['version'])) {
                    $version = $this->options['version'];
                }
                
                $media_embed = UNL_MediaHub_Media_Embed::getById($id, $version, $this->options);

                $this->canView($media_embed);
                
                $this->output[] = $media_embed;
                
                break;
            case 'media_file':
                $this->output[] = UNL_MediaHub_Media_File::getById($this->options['id']);
                break;
            case 'media_oembed':
                $oembed = null;
                if (isset($this->options['url'])) {
                    $oembed = UNL_MediaHub_Media_Oembed::getByURL($this->options['url'], $this->options);
                }

                if ($oembed instanceof UNL_MediaHub_Media_Oembed) {
                    $this->output[] = $oembed;
                } else {
                    http_response_code(404);
                }
                break;
            case 'login':
                $auth = UNL_MediaHub_AuthService::getInstance();
                $auth->login();
                header('Location: ' . UNL_MediaHub_Controller::$url);
                exit();
                break;
            case 'logout':
                $auth = UNL_MediaHub_AuthService::getInstance();
                $auth->logout();
                break;
            case 'media_file_download':
                if (!$media = UNL_MediaHub_Media::getById($this->options['id'])) {
                    throw new \Exception('media not found', 404);
                }
                $file = $media->getLocalFileName();
                if (!empty($file) && file_exists($file)) {
                    $path_info = pathinfo($file);
                    header('Content-Description: Media File Download');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . $media->title . '.' . $path_info['extension'] . '"');
                    header('Content-Length: ' . filesize($file));
                    ob_end_flush();
                    readfile($file);
                }
                exit;
            default:
                $this->output[] = new $this->options['model']($this->options);
            }
        } catch(Exception $e) {
            $this->output[] = $e;
        }
    }

    function displayIframeAccessDeniedMessage($media) {
        $message = 'You do not have permission to view this.';
        if ($media->privacy == 'PROTECTED') {
            $message = 'This media is <strong>PROTECTED</strong> and may only be viewed by logged in users. If you have an account,';
        } elseif ($media->privacy == 'PRIVATE') {
            $message = 'This media is <strong>PRIVATE</strong> and may only be viewed by logged in users with proper channel access. If you have an account and access,';
        } elseif (!$media->meetsCaptionRequirement()) {
            $message = 'This media is not published and may only be viewed by logged in users with proper channel access. If you have an account and access,';
        }
        $message .= ' <a href="' . $media->getURL() . '" target="_blank">you may view on Mediahub</a>.';

        echo '<div style="margin: 1em;"><div style="padding: 1em; color: #424240; background: #fff">';
        echo '<h2>Access Denied</h2>';
        echo '<p>' . $message . '</p>';
        echo '</div></div>';
    }

    function canView($media) {
      if (!$media->canView()) {
        $message = 'You do not have permission to view this.';
        if ($media->privacy == 'PROTECTED') {
          $message = "This media is 'PROTECTED' and may only be viewed by logged in users.  If you have an account, please log in to view.";
        } elseif ($media->privacy == 'PRIVATE') {
          $message = "This media is 'PRIVATE' and may only be viewed by logged in users with proper channel access.  If you have an account and access, please log in to view.";
        } elseif (!$media->meetsCaptionRequirement()) {
          $message = "This media is not published and may only be viewed by logged in users with proper channel access.  If you have an account and access, please log in to view.";
        }
        throw new Exception($message, 403);
      }
    }

    /**
     * Find a specific piece of media.
     *
     * @param array $options Associative array of options $options['id']
     *
     * @throws Exception
     * @return UNL_MediaHub_Media
     */
    function findRequestedMedia($options)
    {
        $media = false;
        if (isset($options['id'])) {
            $media = Doctrine::getTable('UNL_MediaHub_Media')->find($options['id']);
        }
        
        if ($media) {
            return $media;
        }

        throw new Exception('The requested media does not exist.', 404);
    }

    /**
     * @param $post
     * @throws Exception
     */
    protected function handlePost($post)
    {
        // Skip posts for UNL_MediaHub_TranscodeManager
        if ($this->options['model'] === 'UNL_MediaHub_TranscodeManager') {
            return;
        }

        $auth = UNL_MediaHub_AuthService::getInstance();
        
        if (!$media = $this->findRequestedMedia($this->options)) {
            throw new Exception('Media ID must be passed.');
        }
        
        if (isset($post['action'])) {
            switch ($post['action']) {
                case 'playcount':
                    //Log the view.
                    $ip_address = NULL;
                    if (isset($_SERVER['REMOTE_ADDR'])) {
                        $ip_address = $_SERVER['REMOTE_ADDR'];
                    }
                    
                    $view = UNL_MediaHub_MediaView::logView($media, $ip_address);
                    //Don't need to send a response, so stop here.
                    exit();
                    break;
            }
        }

        if (!empty($post['comment'])) {
            if (!$user = $auth->getUser()) {
                throw new Exception('You must be logged in to make a comment.', 403);
            }
            
            if (!$this->validateCSRF()) {
                throw new \Exception('Invalid security token provided. If you think this was an error, please retry the request.', 403);
            }
            
            $data = array(
                'uid'      => $user->uid,
                'media_id' => $media->id,
                'comment'  => $post['comment']
            );

            $comment = new UNL_MediaHub_Media_Comment();
            $comment->fromArray($data);
            $comment->save();
            
            UNL_MediaHub::redirect(self::getURL($media));
        }

        if (!empty($post['tags'])) {
            if (!$user = $auth->getUser()) {
                throw new Exception('You must be logged in to add a tag.', 403);
            }

            if (!$this->validateCSRF()) {
                throw new \Exception('Invalid security token provided. If you think this was an error, please retry the request.', 403);
            }
            
            if (!$media->userCanEdit($user)) {
                throw new Exception('You do not have permission to add a tag.', 403);
            }
            
            foreach (explode(',', $post['tags']) as $tag) {
                $media->addTag(trim($tag));
            }
        }
    }

    /**
     * Called after run - with all output contents.
     *
     * @param string $me The content from the outputcontroller
     *
     * @return string
     */
    function postRun($me)
    {
        $scanned = new \UNL\Templates\Scanner($me);
        
        if (isset(self::$replacements['title'], $scanned->doctitle)) {
            $me = str_replace($scanned->doctitle,
                              '<title>'.self::$replacements['title'].'</title>',
                              $me);
            unset(self::$replacements['title']);
        }

        if (isset(self::$replacements['head'])) {
            $me = str_replace('</head>', self::$replacements['head'].'</head>', $me);
        }

        // TODO: disable breadcrumbs since currently not supported in 5.0 App templates
        /*
        if (isset(self::$replacements['breadcrumbs'], $scanned->breadcrumbs)) {
            $me = str_replace($scanned->breadcrumbs,
                              self::$replacements['breadcrumbs'],
                              $me);
            unset(self::$replacements['breadcrumbs']);
        }
        */

        return $me;
    }

    /**
     * Allows you to set dynamic data when cached output is sent.
     *
     * @param string $field Area to be replaced
     * @param string $data  Data to replace the field with.
     *
     * @return void
     */
    public function setReplacementData($field, $data)
    {
        self::$replacements[$field] = $data;
    }

    /**
     * Gets replacement data
     *
     * @param string $field Key for the data to get
     *
     * @return string|boolean False on failure
     */
    public static function getReplacementData($field)
    {
        if (isset(self::$replacements[$field])) {
            return self::$replacements[$field];
        }

        return false;
    }

    /**
     * Get the URL for the system or a specific object this controller can display.
     *
     * @param mixed $mixed             Optional object to get a URL for.
     * @param array $additional_params Extra parameters to adjust the URL.
     *
     * @return string
     */
    public static function getURL($mixed = null, $additional_params = array())
    {
        $params = array();

        $url = UNL_MediaHub_Controller::$url;

        if (is_object($mixed)) {
            switch (get_class($mixed)) {
            case 'UNL_MediaHub_Media':
                $url .= 'media/'.$mixed->id;
                break;
            case 'UNL_MediaHub_Media_Oembed':
                $url .= 'media/'.$mixed->media->id;
                break;
            case 'UNL_MediaHub_MediaList':
                $url = $mixed->getURL();
                break;
            case 'UNL_MediaHub_Feed':
                $url .= 'channels/'.$mixed->id;
                break;
            case 'UNL_MediaHub_FeedList':
                $url .= 'channels/';
                break;
            default:

            }
        }

        $params = array_merge($params, $additional_params);

        return self::addURLParams($url, $params);
    }
    
    public static function toAgnosticURL($url)
    {
        return str_replace('http://', '//', $url);
    }

    /**
     * Add unique querystring parameters to a URL
     *
     * @param string $url               The URL
     * @param array  $additional_params Additional querystring parameters to add
     *
     * @return string
     */
    public static function addURLParams($url, $additional_params = array())
    {
        $params = array();
        if (strpos($url, '?') !== false) {
            list($url, $existing_params) = explode('?', $url);
            $existing_params = explode('&', $existing_params);
            foreach ($existing_params as $val) {
                list($var, $val) = explode('=', $val);
                $params[$var] = $val;
            }
        }

        $params = array_merge($params, $additional_params);

        $url .= '?';

        foreach ($params as $option=>$value) {
            if (in_array($option, array('driver', 'model', 'filter'))) {
                continue;
            }
            if ($option == 'format'
                && $value == 'html') {
                continue;
            }
            if (!empty($value)) {
                $url .= "&$option=$value";
            }
        }
        $url = str_replace('?&', '?', $url);
        return trim($url, '?;=');
    }

    /**
     * @param string $version
     */
    public static function setVersion($version)
    {
        $file = UNL_MediaHub::getRootDir() . '/version.txt';
        file_put_contents($file, $version);
    }

    /**
     * @return string the current version
     */
    public static function getVersion()
    {
        static $version;

        if ($version) {
            //skip expensive work if we already got it
            return $version;
        }

        $file = UNL_MediaHub::getRootDir() . '/version.txt';
        $version = @file_get_contents($file);

        //Sanitize it so that it can be used in URLs
        $version = htmlentities($version);

        return $version;
    }

    /**
     * Set passed by reference page attributes for
     * frontend and manager controller templates so
     * shared actions are handle here to reduce code
     * duplication.
     *
     * @return null
     */
    public static function sharedTemplatePageActions($siteNotice, $context, &$page, &$savvy) {
        //Navigation
        $page->appcontrols = $savvy->render(null, 'Navigation.tpl.php');

        //Main content
        $page->maincontentarea = '';
        if (!empty($_SESSION['notices']) && is_array($_SESSION['notices'])) {
            foreach ($_SESSION['notices'] as $key=>$notice) {
                $page->maincontentarea .= $savvy->render($notice);
                unset($_SESSION['notices'][$key]);
            }
        }

        $page->maincontentarea .= $savvy->render($context->output);

        if (isset($siteNotice) && $siteNotice->display) {
            $page->displayDCFNoticeMessage($siteNotice->title, $siteNotice->message, $siteNotice->type, $siteNotice->noticePath, $siteNotice->containerID);
        }
        // Add a notice for logged users with:
        // Active AI captions that have not been reviewed OR
        // Videos that currently have no active captions but contain AI-generated captions that could be activated after review.
        $auth = UNL_MediaHub_AuthService::getInstance();
        if($auth->isLoggedIn()) {
            if(count(UNL_MediaHub_User::getMediaWithUnreviewedCaptions($auth->getUser())->items) > 0) {
            $page->displayDCFNoticeMessage('You have unreviewed media captions', "Please review auto generated AI captions for your media for any missed context or inaccuracies. <a href=\"" . htmlspecialchars(UNL_MediaHub_CaptionReviewListManager::getURL()) . "\">View Unreviewed Media Captions.</a>", 'dcf-notice-warning', $siteNotice->noticePath, $siteNotice->containerID);
            }
        }
        
    }

    /**
     * This is only used in the FeedList for checking if the channels feed's model is being used
     * 
     * @return string the current model
     */
    public static function getModel()
    {
        return UNL_MediaHub_Controller::$model;
    }
}

