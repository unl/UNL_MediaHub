<?php
interface UNL_MediaHub_MediaInterface
{

    function getThumbnailURL();
    function isVideo();
    function getVideoDimensions();
    function getVideoTextTrackURL();

}