<?php
/**
 * @var $context UNL_MediaHub_Media
 */
?>
<a href="<?php echo UNL_MediaHub_Controller::getURL($context) ?>">
    <div class="mh-video-thumb wdn-center">
        <img src="<?php echo $context->getThumbnailURL() ?>">
        <div class="mh-play-button"></div>
        <div class="mh-video-label">
            <h6 class="wdn-brand">
                <a href="<?php echo UNL_MediaHub_Controller::getURL($context)?>"><?php echo $context->title ?></a>
                <span class="wdn-subhead"><?php echo date('F j, Y, g:i a', strtotime($context->datecreated)) ?></span>
            </h6>
        </div>
    </div>
</a>