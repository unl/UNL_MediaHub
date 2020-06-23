<?php
/**
 * @var $context UNL_MediaHub_Feed_Media_FeedSelection
 */
?>
<fieldset>
    <legend class="dcf-legend">
        Channels<span class="dcf-required">*</span>
    </legend>
    <ul class="dcf-list-bare validation-container">
        <?php foreach ($context->getFeedSelectionData() as $feed_data): ?>
            <li>
            <?php if ($feed_data['readonly']): ?>
                <input type="hidden" name="feed_id[]" value="<?php echo (int)$feed_data['feed']->id ?>">
            <?php else: ?>
                <div class="dcf-input-checkbox">
                    <input id="feed_id_<?php echo (int)$feed_data['feed']->id ?>"
                            name="feed_id[]"
                            type="checkbox"
                            <?php echo ($feed_data['selected'])?'checked="checked"':''?>
                            value="<?php echo (int)$feed_data['feed']->id ?>" />
                            <label for="feed_id_<?php echo (int)$feed_data['feed']->id ?>">
                                <?php echo UNL_MediaHub::escape($feed_data['feed']->title) ?>
                            </label>
                </div>
            <?php endif; ?>
            </li>
        <?php endforeach;?>
            
        <li><label for="new_feed">New Channel</label><div class="element"><input class="dcf-w-100%" id="new_feed" name="new_feed" type="text" class="validate-one-required"/></div></li>
    </ul>
</fieldset>
