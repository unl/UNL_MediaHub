<form action="<?php echo $this->action; ?>" method="post" name="feed" id="feed">
    <div style="display: none;">
    <input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="feed" />
    <?php
        if (isset($this->feed->id)) {
            echo '<input type="hidden" id="id" name="id" value="'.$this->feed->id.'" />';
        }
    ?>
    </div>

    <fieldset id="feed_header">

        <legend><?php echo (isset($this->feed))?'Edit':'Create'; ?> Feed</legend>
        <ol>
            <li><label for="title" class="element">Title</label><div class="element"><input id="title" name="title" type="text" value="<?php echo $this->feed->title; ?>" size="55" /></div></li>
            <li><label for="description" class="element">Description</label><div class="element"><textarea id="description" name="description" rows="5" cols="50"><?php echo $this->feed->title; ?></textarea></div></li>
            <li><label for="submit" class="element">&nbsp;</label><div class="element"><input id="submit" name="submit" value="Save" type="submit" /></div></li>
        </ol>
    </fieldset>
    
    <?php foreach(array('iTunes'=>'itunes', 'Media RSS'=>'mrss') as $label=>$class) { ?>
    <fieldset id="<?php echo $class; ?>_header">

        <legend><?php echo $label; ?> Options</legend>
        <ol>
            <?php
            $ns_class = 'UNL_MediaYak_Feed_NamespacedElements_'.$class;
            $ns_object = new $ns_class();
            foreach ($ns_object->getChannelElements() as $count=>$element) {
                $nselement = $ns_object->getXMLNS().$element;
                $value = '';
                $label = ucwords($element);
                echo "<li><label for='$class_$element' class='element'>$label</label><div class='element'>
                <input name='$ns_class[$count][nselement]' type='hidden' value='$nselement' />
                <input id='$class_$element' name='$ns_class[$count][value]' type='text' value='$value' size='55' /></div></li>";
            }
            ?>
            <li><label for="<?php echo $class; ?>_submit" class="element">&nbsp;</label><div class="element"><input id="<?php echo $class; ?>_submit" name="submit" value="Save" type="submit" /></div></li>
        </ol>
    </fieldset>
    <?php } ?>
</form>