<?php
/**
 * The Installer is a class to help manage the installation and upgrade of the database
 */
class UNL_MediaHub_Installer
{
    /**
     * @var UNL_MediaHub
     */
    protected $mediahub;
    
    public function __construct(UNL_MediaHub $mediahub)
    {
        $this->mediahub = $mediahub;
    }
    
    public function install()
    {
        return $this->upgrade();
    }
    
    public function upgrade()
    {
        $messages = array();

        $messages[] = $this->exec_sql(file_get_contents(dirname(__FILE__).'/../../../data/mediahub.sql'), 'Initializing database structure');
        $messages[] = $this->exec_sql(file_get_contents(dirname(__FILE__).'/../../../data/add_dateupdated.sql'), 'Add date updated', true);
        $messages[] = $this->exec_sql(file_get_contents(dirname(__FILE__).'/../../../data/add_feed_image.sql'), 'Adding feed image support', true);
        $messages[] = $this->exec_sql(file_get_contents(dirname(__FILE__).'/../../../data/add_featured_feed_fields.sql'), 'Adding featured feeds support', true);
        $messages[] = $this->exec_sql(file_get_contents(dirname(__FILE__).'/../../../data/add_media_privacy.sql'), 'Adding media privacy settings', true);
        $messages[] = $this->exec_sql(file_get_contents(dirname(__FILE__).'/../../../data/add_media_play_count.sql'), 'Adding media play count', true);
        $messages[] = $this->exec_sql(file_get_contents(dirname(__FILE__).'/../../../data/add_media_poster.sql'), 'Adding media poster', true);
        $messages[] = $this->exec_sql(file_get_contents(dirname(__FILE__).'/../../../data/add_media_uid.sql'), 'Adding uidcreated and uidupdated to media', true);
        $messages[] = $this->exec_sql(file_get_contents(dirname(__FILE__).'/../../../data/add_permissions.sql'), 'Adding permissions table', true);
        
        return $messages;
    }
    
    public function uninstall()
    {
        $sql = '
        SET FOREIGN_KEY_CHECKS = 0;
        drop table if exists users;
        drop table if exists feed_has_media;
        drop table if exists feed_has_nselement;
        drop table if exists feed_has_subscription;
        drop table if exists feeds;
        drop table if exists media;
        drop table if exists media_has_nselement;
        drop table if exists subscriptions;
        drop table if exists user_has_permission;
        drop table if exists permissions;
        SET FOREIGN_KEY_CHECKS = 1;
        ';
        
        return $this->exec_sql($sql, 'Uninstall');
    }

    protected function exec_sql($sql, $message, $fail_ok = false)
    {
        $db = $this->mediahub->getDB();

        try {
            $result = $db->execute($sql);
        } catch (Exception $e) {
            if (!$fail_ok) {
                throw new UNL_MediaHub_RuntimeException('Query Failed: "' . $message . '" - ' . $db->errorInfo(), 500);
            }
        }
        
        return $message;
    }
}
