<div class="three_col left">
    <ul class="wdn_tabs">
        <li><a href="#top_media">Top</a></li>
        <li><a href="#latest_media">Latest</a></li>
    </ul>
    <div class="wdn_tabs_content">
        <div id="top_media">
            <?php echo $savvy->render($context->top_media); ?>
        </div>
        <div id="latest_media">
            <?php echo $savvy->render($context->latest_media); ?>
        </div>
    </div>
    
</div>
<div class="col right">
    <a href="<?php echo UNL_MediaYak_Controller::getURL(); ?>manager/">Add your media</a>
    <?php
    echo $savvy->render($context->featured_channels);
    ?>
    <a href="<?php echo UNL_MediaYak_Controller::getURL(); ?>channels/">See all channels</a>
</div>