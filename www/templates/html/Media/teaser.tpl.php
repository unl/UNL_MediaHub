<?php
/**
 * @var $context UNL_MediaHub_Media
 */

$user = UNL_MediaHub_AuthService::getInstance()->getUser();
?>
<div class="mh-video-thumb dcf-txt-center">
    <a href="<?php echo UNL_MediaHub_Controller::getURL($context) ?>">
        <div class="mh-thumbnail-clip">
            <img src="<?php echo $context->getThumbnailURL() ?>" alt="<?php echo UNL_MediaHub::escape($context->title) ?>">
        </div>
        <div class="mh-play-button"></div>
    </a>
    <?php if ($user && $context->userCanEdit($user)): ?>
        <a href="<?php echo UNL_MediaHub_Manager::getURL() . '?view=addmedia&amp;id=' . $context->id ?>" class="edit-button dcf-btn dcf-btn-primary">Edit</a>
    <?php endif; ?>
</div>
<div class="mh-video-label dcf-txt-center">
    <p class="dcf-txt-md">
        <span class="dcf-subhead">
            <?php echo date('F j, Y, g:i a', strtotime($context->datecreated)) ?>
        </span>
        <a class="dcf-txt-decor-none" href="<?php echo UNL_MediaHub_Controller::getURL($context) ?>"><?php echo UNL_MediaHub::escape($context->title); ?></a>
    </p>
</div>