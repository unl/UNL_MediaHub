<h1><?php echo (isset($this->media))?'Edit':'Add'; ?> Media</h1>
<form action="?view=feed" method="post" name="media" id="media" enctype="multipart/form-data">
    <div style="display: none;">
        <input type="hidden" id="feed_id" name="feed_id" value="<?php echo (int)$_GET['feed_id']; ?>" />
        <input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="feed_media" />
        <input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="67108864" />
        <?php
        if (isset($this->media->id)) {
            echo '<input type="hidden" id="id" name="id" value="'.$this->media->id.'" />';
        }
        ?>
    </div>
    <fieldset id="existing_media">
        <legend>Existing Media</legend>
        <ol>
            <li><label for="url" class="element">URL</label><div class="element"><input id="url" name="url" type="text" size="60" value="<?php echo htmlentities(@$this->media->url, ENT_QUOTES); ?>" /></div></li>
            <li><label for="title" class="element">Title</label><div class="element"><input id="title" name="title" type="text" size="60" value="<?php echo htmlentities(@$this->media->title, ENT_QUOTES); ?>" /></div></li>
            <li>
                <label for="description" class="element">Description</label>
                <div class="element"><textarea id="description" name="description" rows="5" cols="60"><?php echo htmlentities(@$this->media->description); ?></textarea></div>
            </li>

            <li><label for="submit_existing" class="element">&nbsp;</label><div class="element"><input id="submit_existing" name="submit_existing" value="Save" type="submit" /></div></li>
        </ol>
    </fieldset>
    <?php foreach(array('iTunes'=>'itunes', 'Media RSS'=>'mrss') as $label=>$class) { ?>
    <fieldset id="<?php echo $class; ?>_header">

        <legend><?php echo $label; ?> Options</legend>
        <ol>
            <?php
            $ns_class = 'UNL_MediaYak_Feed_Media_NamespacedElements_'.$class;
            $ns_object = new $ns_class();
            $count = 0;
            foreach ($ns_object->getItemElements() as $count=>$element) {
                $nselement = $ns_object->getXMLNS().':'.$element;
                $value = '';
                if (isset($this->media) && count($this->media->$ns_class)) {
                    foreach ($this->media->$ns_class as $ns_record) {
                        if ($ns_record['nselement'] == $nselement) {
                            $value = htmlentities($ns_record['value'], ENT_QUOTES);
                            break;
                        }
                    }
                }
                $label = ucwords($element);
                echo "<li><label for='{$class}_{$element}' class='element'>$label</label><div class='element'>
                <input name='{$ns_class}[{$count}][nselement]' type='hidden' value='$nselement' />
                <input id='{$class}_{$element}' name='{$ns_class}[{$count}][value]' type='text' value='$value' size='55' /></div></li>";
                $count++;
            }
            ?>
            <li><label for="<?php echo $class; ?>_submit" class="element">&nbsp;</label><div class="element"><input id="<?php echo $class; ?>_submit" name="submit" value="Save" type="submit" /></div></li>
        </ol>
    </fieldset>
    <?php }
    if (!isset($this->media)) {
    ?>
    <fieldset id="new_media">
        <legend>Upload new media</legend>
        <ol>
            <li><label for="file_upload" class="element">File</label><div class="element"><input id="file_upload" name="file_upload" type="file" /></div></li>
            <li><label for="qf_4f7d51" class="element">What would you like to do with this file?</label><div class="element"><input name="upload_target" type="radio" id="qf_4f7d51" /><label for="qf_4f7d51">Encode it</label></div></li>

            <li><label for="qf_a38a81" class="element">&nbsp;</label><div class="element"><input name="upload_target" type="radio" id="qf_a38a81" /><label for="qf_a38a81">Go Live</label></div></li>
            <li><label for="submit_new" class="element">&nbsp;</label><div class="element"><input id="submit_new" name="submit_new" value="Save" type="submit" /></div></li>
        </ol>
    </fieldset>
    <?php
    }
    ?>
</form>