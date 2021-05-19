<?php
/**
 * @var $context UNL_MediaHub_Media
 */

$user = UNL_MediaHub_AuthService::getInstance()->getUser();
?>
<div class="dcf-card-as-link">
    <div class="dcf-relative mh-video-thumb">
        <div class="dcf-ratio dcf-ratio-16x9 mh-thumbnail-clip">
            <img class="dcf-ratio-child dcf-obj-fit-cover" src="<?php echo $context->getThumbnailURL() ?>" alt="<?php echo UNL_MediaHub::escape($context->title) ?>">
        </div>
        <div class="mh-play-button"></div>
        <?php if ($user && $context->userCanEdit($user)): ?>
            <a href="<?php echo UNL_MediaHub_Manager::getURL() . '?view=addmedia&amp;id=' . $context->id ?>" class="edit-button dcf-btn dcf-btn-secondary dcf-absolute dcf-pin-bottom dcf-pin-right dcf-mb-1 dcf-mr-1">Edit</a>
        <?php endif; ?>
    </div>
    <div class="mh-video-label dcf-txt-center">
        <p class="dcf-txt-md">
            <span class="dcf-subhead">
                <?php echo date('F j, Y, g:i a', strtotime($context->datecreated)) ?>
            </span><br>
            <a class="dcf-card-link dcf-txt-decor-none" href="<?php echo UNL_MediaHub_Controller::getURL($context) ?>"><?php echo UNL_MediaHub::escape($context->title); ?></a>
        </p>
        <?php if ($user && $context->userCanEdit($user)): ?>
        <p class="dcf-txt-xs"><?php echo ucfirst(strtolower($context->privacy)); ?></p>
        <?php endif; ?>
    </div>
</div>
