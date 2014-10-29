<label for="channels">Channels
    <span class="required">*</span>
</label>
<div class="mh-channel-box">
    <ul>
        <?php
        $list = UNL_MediaHub_Manager::getUser()->getFeeds(array('limit'=>300));
        echo $savvy->render($list, 'Feed/Media/FeedList.tpl.php');
        ?>
        <li><label for="new_feed" class="element">New Channel</label><div class="element"><input id="new_feed" name="new_feed" type="text" /></div></li>
    </ul>
</div>
