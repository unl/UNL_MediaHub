<ol>
    <li>
        <fieldset>
            <legend>Select from your channel or add to a new channel</legend>
                <ol>
                    <?php
                    $list = UNL_MediaYak_Manager::getUser()->getFeeds(array('limit'=>300));
                    echo $savvy->render($list, 'Feed/Media/FeedList.tpl.php');
                    ?>
                    <li><label for="new_feed" class="element">New Channel</label><div class="element"><input id="new_feed" name="new_feed" type="text" /></div></li>
                </ol>
        </fieldset>
    </li>
</ol>
