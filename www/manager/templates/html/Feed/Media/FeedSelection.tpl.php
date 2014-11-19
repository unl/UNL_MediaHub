<?php
/**
 * @var $context UNL_MediaHub_Feed_Media_FeedSelection
 */
?>
<fieldset>
    <legend>
        Channels<span class="required">*</span>
    </legend>
    <div class="mh-channel-box">
        <ul class="validation-container">
            <?php foreach ($context->getFeedSelectionData() as $feed_data): ?>
                <li>
                    <input 
                        id="feed_id_<?php echo $feed_data['feed']->id ?>"
                        name="feed_id[]"
                        type="checkbox"
                        <?php echo ($feed_data['selected'])?'checked="checked"':''?>
                        <?php echo ($feed_data['readonly'])?'readonly="readonly"':''?>
                        value="<?php echo $feed_data['feed']->id ?>" />
                    <label for="feed_id_<?php echo $feed_data['feed']->id ?>">
                        <?php echo $feed_data['feed']->title ?>
                    </label>
                </li>
            <?php endforeach;?>
            
            <li><label for="new_feed" class="element">New Channel</label><div class="element"><input id="new_feed" name="new_feed" type="text" class="validate-one-required"/></div></li>
        </ul>
    </div>
</fieldset>
