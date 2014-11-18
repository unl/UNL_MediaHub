<fieldset>
    <legend>
        Channels<span class="required">*</span>
    </legend>
    <div class="mh-channel-box">
        <ul class="validation-container">
            <?php
            $list = UNL_MediaHub_Manager::getUser()->getFeeds(array('limit'=>300));
            echo $savvy->render($list, 'Feed/Media/FeedList.tpl.php');
            ?>
            <li><label for="new_feed" class="element">New Channel</label><div class="element"><input id="new_feed" name="new_feed" type="text" class="validate-one-required"/></div></li>
        </ul>
    </div>
</fieldset>
