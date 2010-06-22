<form action="<?php echo $context->action; ?>" method="post" name="feed" id="feed" enctype="multipart/form-data">
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
    
    <?php foreach(array('iTunes'=>'itunes', 'Media RSS'=>'media') as $label=>$class) { ?>
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
    <?php } ?>
</form>