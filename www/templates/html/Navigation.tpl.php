<?php
    $baseUrl = UNL_MediaHub_Controller::getURL();
    $auth = UNL_MediaHub_AuthService::getInstance();

?>
<ul>
    <li><a href="<?php echo $baseUrl ?>search/">Browse Media</a>
    <li><a href="<?php echo $baseUrl ?>channels/">Channels</a></li>
    <li><a href="<?php echo $baseUrl ?>manager/">Manage Media</a></li>
    <?php if ($auth->isLoggedIn()) { ?>
    <li><a href="<?php echo UNL_MediaHub_CaptionReviewListManager::getURL(); ?>">Unreviewed Media Captions</a></li>
        <?php if ($auth->getUser()->isAdmin()) { ?>
            <li><a href="<?php echo UNL_MediaHub_Controller::getURL(); ?>transcode-manager">Transcode Manager</a></li>
            <li><a href="<?php echo UNL_MediaHub_TranscriptionManager::getURL(); ?>">Transcription Manager</a></li>
        <?php } ?>
        <li><a href="<?php echo $baseUrl ?>logout/">Log out as <?php echo $auth->getUserDisplayName(); ?></a></li>
    <?php } else { ?>
        <li><a href="<?php echo $baseUrl ?>login/">Log In</a></li>
    <?php } ?>
</ul>
