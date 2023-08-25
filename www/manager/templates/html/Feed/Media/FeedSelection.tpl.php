<?php
/**
 * @var $context UNL_MediaHub_Feed_Media_FeedSelection
 */
?>
<fieldset class="dcf-pb-0">
    <legend class="dcf-legend">
        Channels <small class="dcf-required">Required</small>
    </legend>
    <div
        class="dcf-overflow-y-auto dcf-pr-5 dcf-pb-4"
        style="max-height: 20rem; margin-right: calc(-3.16vw + 1px)!important;"
    >
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
                                value="<?php echo (int)$feed_data['feed']->id ?>"
                        />
                        <label for="feed_id_<?php echo (int)$feed_data['feed']->id ?>">
                            <?php echo UNL_MediaHub::escape($feed_data['feed']->title) ?>
                        </label>
                    </div>
                <?php endif; ?>
                </li>
            <?php endforeach;?>
            <li>
                <label for="new_feed">New Channel</label>
                <div class="element">
                    <input
                        class="dcf-w-100%"
                        id="new_feed"
                        name="new_feed"
                        type="text"
                        class="validate-one-required"
                    />
                </div>
            </li>
        </ul>
    <div>
</fieldset>
