<?php
/**
 * @var $context UNL_MediaHub_Media
 */
?>
<div class="mh-video-thumb wdn-center">
    <a href="<?php echo UNL_MediaHub_Controller::getURL($context) ?>">
        <div class="mh-thumbnail-clip">
            <img src="<?php echo $context->getThumbnailURL() ?>" alt="<?php echo htmlentities($context->title, ENT_QUOTES) ?>">
        </div>
        <div class="mh-play-button"></div>
    </a>
    <?php if ($user && $context->userCanEdit($user)): ?>
        <a href="<?php echo UNL_MediaHub_Manager::getURL() . '?view=addmedia&amp;id=' . $context->id ?>" class="edit-button wdn-button wdn-button-brand">Edit</a>
    <?php endif; ?>
</div>
<div class="mh-video-label wdn-center">
    <p>
        <span class="wdn-subhead">
            <?php echo date('F j, Y, g:i a', strtotime($context->datecreated)) ?>
        </span>
        <a href="<?php echo UNL_MediaHub_Controller::getURL($context) ?>"><?php echo htmlentities($context->title, ENT_QUOTES); ?></a>
    </p>
</div>