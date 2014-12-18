<?php

class UNL_MediaHub_MediaView extends UNL_MediaHub_Models_BaseMediaView
{
    public static function logView(UNL_MediaHub_Media $media, $ip_address = null)
    {
        //Log the view (with relevant meta-data)
        $view = new self();
        
        $data = array(
            'media_id'     => $media->id,
            'datecreated'  => date('Y-m-d H:i:s'),
            'ip_address'   => $ip_address,
        );
        $view->fromArray($data);

        $view->save();

        //Update the media play count
        $media->play_count++;
        $media->save();
        
        return $view;
    }
    
}