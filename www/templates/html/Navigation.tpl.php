<?php
    $baseUrl = UNL_MediaHub_Controller::getURL();
    $auth = UNL_MediaHub_AuthService::getInstance();

?>
<ul role="list">
    <li><a href="<?php echo $baseUrl ?>search/">Browse Media</a>
    <li><a href="<?php echo $baseUrl ?>channels/">Channels</a></li>
    <?php if (!$auth->isLoggedIn()): ?>
        <li><a href="<?php echo $baseUrl ?>manager/">Manage Media</a></li>
    <?php else: ?>
        <li>
            <a href="<?php echo $baseUrl ?>manager/">Manage Media</a>
            <ul role="list">
                <li><a href="<?php echo UNL_MediaHub_CaptionReviewListManager::getURL(); ?>">Unreviewed Captions</a></li>
            </ul>
        </li>
        <?php if ($auth->getUser()->isAdmin()): ?>
            <li>
                <a>Admin</a>
                <ul role="list">
                    <li><a href="<?php echo UNL_MediaHub_Controller::getURL(); ?>transcode-manager">Transcode Manager</a></li>
                    <li><a href="<?php echo UNL_MediaHub_TranscriptionManager::getURL(); ?>">Transcription Manager</a></li>
                </ul>
            </li>
        <?php endif; ?>
    <?php endif; ?>
</ul>
