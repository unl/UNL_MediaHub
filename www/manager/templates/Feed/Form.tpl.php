<form action="<?php echo $context->action; ?>" method="post" name="feed" id="feed" enctype="multipart/form-data" class="zenform">
    <div style="display: none;">
    <input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="feed" />
    <?php
        if (isset($context->feed->id)) {
            echo '<input type="hidden" id="id" name="id" value="'.$context->feed->id.'" />';
        }
    ?>
    </div>

    <fieldset id="feed_header">

        <legend><?php echo (isset($context->feed))?'Edit':'Create'; ?> Feed</legend>
        <ol>
            <li><label for="title" class="element">Title</label><div class="element"><input id="title" name="title" type="text" value="<?php echo (isset($context->feed))? htmlentities($context->feed->title, ENT_QUOTES):''; ?>" size="55" /></div></li>
            <li><label for="description" class="element">Description</label><div class="element"><textarea id="description" name="description" rows="5" cols="50"><?php echo (isset($context->feed))?htmlentities($context->feed->description):''; ?></textarea></div></li>
            <li><label class="element">Image:</label>
                <div class="element">
                <p>Images should follow the standard UNL image standards.<br />For a sample template, click here:
                <a href="http://ucommdev.unl.edu/iTunesU/design_files/icon_template.psd">iTunes U icon template</a><br /><br /></p>

                <ol>
                    <li><label for="image_file" class="element">File</label><div class="element"><input id="image_file" name="image_file" type="file" /></div></li>
                    <li><label for="image_title" class="element">Image Title</label><div class="element"><input id="image_title" name="image_title" type="text" value="<?php echo (isset($context->feed))? htmlentities($context->feed->image_title, ENT_QUOTES):''; ?>" size="55" /></div></li>
                    <li><label for="image_description" class="element">Image Description</label><div class="element"><textarea id="image_description" name="image_description" rows="5" cols="50"><?php echo (isset($context->feed))?htmlentities($context->feed->image_description):''; ?></textarea></div></li>
                </ol>
                </div>
            </li>
            <li><label for="submit" class="element">&nbsp;</label><div class="element"><input id="submit" name="submit" value="Save" type="submit" /></div></li>
        </ol>
    </fieldset>
    <?php
    function getFieldValue($context, $xmlns, $element)
    {
        if (isset($context->feed)) {
            $class = 'UNL_MediaYak_Feed_NamespacedElements_'.$xmlns;
            if ($element = call_user_func($class .'::feedHasElement', $context->feed->id, $element, $xmlns)) {
                return htmlentities($element->value, ENT_QUOTES);
            }
        }
        return '';
    }
    function getFieldAttributes($context, $xmlns, $element)
    {
        if (isset($context->feed)) {
            $class = 'UNL_MediaYak_Feed_NamespacedElements_'.$xmlns;
            if ($element = call_user_func($class .'::feedHasElement', $context->feed->id, $element, $xmlns)) {
                return htmlentities(serialize($element->attributes), ENT_QUOTES);
            }
        }
        return '';
    }
    ?>
    <fieldset id="itunes_header">

        <legend>iTunes Options</legend>

        <ol>
            <li><label for='itunes_author' class='element'>Author</label><div class='element'>
                <input name='UNL_MediaYak_Feed_NamespacedElements_itunes[0][element]' type='hidden' value='author' />
                <input id='itunes_author' name='UNL_MediaYak_Feed_NamespacedElements_itunes[0][value]' type='text' value='<?php echo getFieldValue($context, 'itunes', 'author'); ?>' size='55' />
                </div></li><li><label for='itunes_block' class='element'>Block</label><div class='element'>
                <input name='UNL_MediaYak_Feed_NamespacedElements_itunes[1][element]' type='hidden' value='block' />
                <input id='itunes_block' name='UNL_MediaYak_Feed_NamespacedElements_itunes[1][value]' type='text' value='<?php echo getFieldValue($context, 'itunes', 'block'); ?>' size='55' />
                </div></li><li><label for='itunes_category' class='element'>Category</label><div class='element'>

                <input name='UNL_MediaYak_Feed_NamespacedElements_itunes[2][element]' type='hidden' value='category' />
                <input id='itunes_category' name='UNL_MediaYak_Feed_NamespacedElements_itunes[2][value]' type='text' value='<?php echo getFieldValue($context, 'itunes', 'category'); ?>' size='55' />
                </div></li><li><label for='itunes_image' class='element'>Image</label><div class='element'>
                <input name='UNL_MediaYak_Feed_NamespacedElements_itunes[3][element]' type='hidden' value='image' />
                <input id='itunes_image' name='UNL_MediaYak_Feed_NamespacedElements_itunes[3][value]' type='text' value='<?php echo getFieldValue($context, 'itunes', 'image'); ?>' size='55' />
                </div></li>
                
                <li><label for='itunes_explicit' class='element'>Explicit</label>
                <div class='element'>
                    <input name="UNL_MediaYak_Feed_NamespacedElements_itunes[4][element]" type="hidden" value="explicit"/>
                    <select id="itunes_explicit" name="UNL_MediaYak_Feed_NamespacedElements_itunes[4][value]">
                        <?php
                        if (getFieldValue($context, 'itunes', 'explicit') == "yes") {
                            echo '<option value="">No</option><option value="yes" selected="selected">Yes</option>';
                        } else {
                            echo '<option value="">No</option><option value="yes">Yes</option>';
                        }
                        ?>
                    </select>
                    <dl class="caption"><dd>Set to 'yes' if this feed contains explicit content</dd></dl>
                </div></li>
                
                <li><label for='itunes_keywords' class='element'>Keywords</label><div class='element'>
                <input name='UNL_MediaYak_Feed_NamespacedElements_itunes[5][element]' type='hidden' value='keywords' />
                <input id='itunes_keywords' name='UNL_MediaYak_Feed_NamespacedElements_itunes[5][value]' type='text' value='<?php echo getFieldValue($context, 'itunes', 'keywords'); ?>' size='55' />
                </div></li><li><label for='itunes_new-feed-url' class='element'>New-feed-url</label><div class='element'>
                <input name='UNL_MediaYak_Feed_NamespacedElements_itunes[6][element]' type='hidden' value='new-feed-url' />
                <input id='itunes_new-feed-url' name='UNL_MediaYak_Feed_NamespacedElements_itunes[6][value]' type='text' value='<?php echo getFieldValue($context, 'itunes', 'new-feed-url'); ?>' size='55' />
                </div></li><li><label for='itunes_owner' class='element'>Owner</label><div class='element'>

                <input name='UNL_MediaYak_Feed_NamespacedElements_itunes[7][element]' type='hidden' value='owner' />
                <input id='itunes_owner' name='UNL_MediaYak_Feed_NamespacedElements_itunes[7][value]' type='text' value='<?php echo getFieldValue($context, 'itunes', 'owner'); ?>' size='55' />
                </div></li><li><label for='itunes_subtitle' class='element'>Subtitle</label><div class='element'>
                <input name='UNL_MediaYak_Feed_NamespacedElements_itunes[8][element]' type='hidden' value='subtitle' />
                <input id='itunes_subtitle' name='UNL_MediaYak_Feed_NamespacedElements_itunes[8][value]' type='text' value='<?php echo getFieldValue($context, 'itunes', 'subtitle'); ?>' size='55' />
                </div></li><li><label for='itunes_summary' class='element'>Summary</label><div class='element'>
                <input name='UNL_MediaYak_Feed_NamespacedElements_itunes[9][element]' type='hidden' value='summary' />
                <input id='itunes_summary' name='UNL_MediaYak_Feed_NamespacedElements_itunes[9][value]' type='text' value='<?php echo getFieldValue($context, 'itunes', 'summary'); ?>' size='55' />

                </div></li>            <li><label for="itunes_submit" class="element">&nbsp;</label><div class="element"><input id="itunes_submit" name="submit" value="Save" type="submit" /></div></li>
        </ol>
    </fieldset>
        <fieldset id="media_header">

        <legend>Media RSS Options</legend>
        <ol>
            <li><label for='media_rating' class='element'>Rating</label><div class='element'>

                <input name='UNL_MediaYak_Feed_NamespacedElements_media[0][element]' type='hidden' value='rating' />
                <input id='media_rating' name='UNL_MediaYak_Feed_NamespacedElements_media[0][value]' type='text' value='<?php echo getFieldValue($context, 'media', 'rating'); ?>' size='55' />
                </div></li><li><label for='media_title' class='element'>Title</label><div class='element'>
                <input name='UNL_MediaYak_Feed_NamespacedElements_media[1][element]' type='hidden' value='title' />
                <input id='media_title' name='UNL_MediaYak_Feed_NamespacedElements_media[1][value]' type='text' value='<?php echo getFieldValue($context, 'media', 'title'); ?>' size='55' />
                </div></li><li><label for='media_description' class='element'>Description</label><div class='element'>
                <input name='UNL_MediaYak_Feed_NamespacedElements_media[2][element]' type='hidden' value='description' />
                <input id='media_description' name='UNL_MediaYak_Feed_NamespacedElements_media[2][value]' type='text' value='<?php echo getFieldValue($context, 'media', 'description'); ?>' size='55' />

                </div></li><li><label for='media_keywords' class='element'>Keywords</label><div class='element'>
                <input name='UNL_MediaYak_Feed_NamespacedElements_media[3][element]' type='hidden' value='keywords' />
                <input id='media_keywords' name='UNL_MediaYak_Feed_NamespacedElements_media[3][value]' type='text' value='<?php echo getFieldValue($context, 'media', 'keywords'); ?>' size='55' />
                </div></li><li><label for='media_thumbnail' class='element'>Thumbnail</label><div class='element'>
                <input name='UNL_MediaYak_Feed_NamespacedElements_media[4][element]' type='hidden' value='thumbnail' />
                <input id='media_thumbnail' name='UNL_MediaYak_Feed_NamespacedElements_media[4][value]' type='text' value='<?php echo getFieldValue($context, 'media', 'thumbnail'); ?>' size='55' />
                </div></li><li><label for='media_category' class='element'>Category</label><div class='element'>

                <input name='UNL_MediaYak_Feed_NamespacedElements_media[5][element]' type='hidden' value='category' />
                <input id='media_category' name='UNL_MediaYak_Feed_NamespacedElements_media[5][value]' type='text' value='<?php echo getFieldValue($context, 'media', 'category'); ?>' size='55' />
                </div></li><li><label for='media_player' class='element'>Player</label><div class='element'>
                <input name='UNL_MediaYak_Feed_NamespacedElements_media[6][element]' type='hidden' value='player' />
                <input id='media_player' name='UNL_MediaYak_Feed_NamespacedElements_media[6][value]' type='text' value='<?php echo getFieldValue($context, 'media', 'player'); ?>' size='55' />
                </div></li><li><label for='media_credit' class='element'>Credit</label><div class='element'>
                <input name='UNL_MediaYak_Feed_NamespacedElements_media[7][element]' type='hidden' value='credit' />
                <input id='media_credit' name='UNL_MediaYak_Feed_NamespacedElements_media[7][value]' type='text' value='<?php echo getFieldValue($context, 'media', 'credit'); ?>' size='55' />

                </div></li><li><label for='media_copyright' class='element'>Copyright</label><div class='element'>
                <input name='UNL_MediaYak_Feed_NamespacedElements_media[8][element]' type='hidden' value='copyright' />
                <input id='media_copyright' name='UNL_MediaYak_Feed_NamespacedElements_media[8][value]' type='text' value='<?php echo getFieldValue($context, 'media', 'copyright'); ?>' size='55' />
                </div></li><li><label for='media_text' class='element'>Text</label><div class='element'>
                <input name='UNL_MediaYak_Feed_NamespacedElements_media[9][element]' type='hidden' value='text' />
                <input id='media_text' name='UNL_MediaYak_Feed_NamespacedElements_media[9][value]' type='text' value='<?php echo getFieldValue($context, 'media', 'text'); ?>' size='55' />
                </div></li><li><label for='media_restriction' class='element'>Restriction</label><div class='element'>

                <input name='UNL_MediaYak_Feed_NamespacedElements_media[10][element]' type='hidden' value='restriction' />
                <input id='media_restriction' name='UNL_MediaYak_Feed_NamespacedElements_media[10][value]' type='text' value='<?php echo getFieldValue($context, 'media', 'restriction'); ?>' size='55' />
                </div></li>            <li><label for="media_submit" class="element">&nbsp;</label><div class="element"><input id="media_submit" name="submit" value="Save" type="submit" /></div></li>
        </ol>
    </fieldset>
        <fieldset id="boxee_header">

        <legend>Boxee Options</legend>

        <ol>
            <li><label for='boxee_expiry' class='element'>Expiry</label><div class='element'>
                <input name='UNL_MediaYak_Feed_NamespacedElements_boxee[0][element]' type='hidden' value='expiry' />
                <input id='boxee_expiry' name='UNL_MediaYak_Feed_NamespacedElements_boxee[0][value]' type='text' value='<?php echo getFieldValue($context, 'boxee', 'expiry'); ?>' size='55' />
                </div></li><li><label for='boxee_background-image' class='element'>Background-image</label><div class='element'>
                <input name='UNL_MediaYak_Feed_NamespacedElements_boxee[1][element]' type='hidden' value='background-image' />
                <input id='boxee_background-image' name='UNL_MediaYak_Feed_NamespacedElements_boxee[1][value]' type='text' value='<?php echo getFieldValue($context, 'boxee', 'background-image'); ?>' size='55' />
                </div></li><li><label for='boxee_interval' class='element'>Interval</label><div class='element'>

                <input name='UNL_MediaYak_Feed_NamespacedElements_boxee[2][element]' type='hidden' value='interval' />
                <input id='boxee_interval' name='UNL_MediaYak_Feed_NamespacedElements_boxee[2][value]' type='text' value='<?php echo getFieldValue($context, 'boxee', 'interval'); ?>' size='55' />
                </div></li><li><label for='boxee_category' class='element'>Category</label><div class='element'>
                <input name='UNL_MediaYak_Feed_NamespacedElements_boxee[3][element]' type='hidden' value='category' />
                <input id='boxee_category' name='UNL_MediaYak_Feed_NamespacedElements_boxee[3][value]' type='text' value='<?php echo getFieldValue($context, 'boxee', 'category'); ?>' size='55' />
                </div></li>            <li><label for="boxee_submit" class="element">&nbsp;</label><div class="element"><input id="boxee_submit" name="submit" value="Save" type="submit" /></div></li>
        </ol>
    </fieldset>
    
    <?php
    // Disable this rendering method for now since fields are hardcoded.
    if (false) {
    foreach(array('iTunes'=>'itunes', 'Media RSS'=>'media', 'Boxee'=>'boxee') as $label=>$class) { ?>
    <fieldset id="<?php echo $class; ?>_header">

        <legend><?php echo $label; ?> Options</legend>
        <ol>
            <?php
            $ns_class = 'UNL_MediaYak_Feed_NamespacedElements_'.$class;
            $ns_object = new $ns_class();
            $count = 0;
            foreach ($ns_object->getChannelElements() as $count=>$element) {
                $value = '';
                if (count($context->feed->$ns_class)) {
                    foreach ($context->feed->$ns_class as $ns_record) {
                        if ($ns_record['element'] == $element) {
                            $value = htmlentities($ns_record['value'], ENT_QUOTES);
                            break;
                        }
                    }
                }
                $label = ucwords($element);
                echo "<li><label for='{$class}_{$element}' class='element'>$label</label><div class='element'>
                <input name='{$ns_class}[{$count}][element]' type='hidden' value='$element' />
                <input id='{$class}_{$element}' name='{$ns_class}[{$count}][value]' type='text' value='$value' size='55' />
                </div></li>";
                $count++;
            }
            ?>
            <li><label for="<?php echo $class; ?>_submit" class="element">&nbsp;</label><div class="element"><input id="<?php echo $class; ?>_submit" name="submit" value="Save" type="submit" /></div></li>
        </ol>
    </fieldset>
    <?php }
    } ?>
</form>