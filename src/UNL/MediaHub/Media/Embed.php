<?php
class UNL_MediaHub_Media_Embed
{
    /**
     * @var UNL_MediaHub_Media
     */
    public $media;

    /**
     * @var int
     */
    public $version = 1;

    /**
     * @var array
     */
    public $options = array();

    /**
     * @var array
     */
    protected $allowed_versions = array(1, 2, 3);

    function __construct(UNL_MediaHub_Media $media = null, $version = 1, $options = array())
    {
        //Validate the version
        if (!$this->versionIsAllowed($version)) {
            throw new Exception('The version "' . $version . '" is not allowed', 500);
        }
        
        $this->media = $media;
        $this->version = $version;
        $this->options += $options;
    }

    /**
     * Check if a given version is allowed
     * 
     * @param $version
     * @return bool
     */
    function versionIsAllowed($version) {
        if (!in_array($version, $this->allowed_versions)) {
            return false;
        }
        
        return true;
    }
    
    public function meetsCaptionRequirement()
    {
        return $this->media->meetsCaptionRequirement();
    }

    public function canView($user)
    {
        return $this->media->canView($user);
    }
    
    /**
     * Get by ID
     *
     * @param int $id The id of the media to get
     *
     * @param int $version
     * @param array $options
     * @return UNL_MediaHub_Media_Embed
     */
    static function getById($id = null, $version = 1, $options = array())
    {
        if (!$id) {
            return new self();
        }
        
        return new self(Doctrine::getTable('UNL_MediaHub_Media')->find($id), $version, $options);
    }
}
