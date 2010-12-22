<div class="three_col left">
    <ul class="wdn_tabs">
        <li class="selected"><a href="#top_media">Top</a></li>
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
    <h3 class="sec_main">Explore Media Hub</h3>
</div>
<div class="col right">
    <a href="<?php echo UNL_MediaYak_Controller::getURL(); ?>manager/" class="action">Add your media</a>
    <?php
    echo $savvy->render($context->featured_channels);
    ?>
    <a href="<?php echo UNL_MediaYak_Controller::getURL(); ?>channels/">See all channels</a>
</div>