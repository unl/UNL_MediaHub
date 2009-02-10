<?php

$jquery = '';
if (!isset($this->media)) {
    $jquery .= '$("#part2").hide();';
} else {
    $jquery .= '$("#part1").hide();';
}

$jquery .= '
    $("#continue2").click(function() {
            $("#part1").hide(400);
            $("#part2").show(400);
            return false;
        }
    );';
$jquery .= '
    $("#itunes_header ol").hide();
    $("#mrss_header ol").hide();
    
    $("#itunes_header legend").click(function() {
      $("#itunes_header ol").toggle(400);
      return false;
    });
    $("#mrss_header legend").click(function() {
      $("#mrss_header ol").toggle(400);
      return false;
    });';

UNL_MediaYak_Manager::setReplacementData('head','
<script type="text/javascript">
$(document).ready(function() {
    '.$jquery.'
});

</script>
');

?>
<form action="?view=feed" method="post" name="media_form" id="media_form" enctype="multipart/form-data">
<div id="part1">
    <h1>Add new media:</h1>
    <fieldset id="add_media">
        <legend>Add new media:</legend>
        <ol>
            <li><label for="url" class="element">Add by URL</label><div class="element"><input id="url" name="url" type="text" size="60" value="<?php echo htmlentities(@$this->media->url, ENT_QUOTES); ?>" /></div></li>
            <li><label for="file_upload" class="element">Upload a file</label><div class="element"><input id="file_upload" name="file_upload" type="file" /></div></li>
        </ol>
    </fieldset>
    <a class="continue" id="continue2" href="#">Continue</a>
</div>
<div id="part2">
<h1><?php echo (isset($this->media))?'Edit the details of your':'Tell us about your'; ?> media.</h1>
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
                $value = '';
                if (isset($this->media) && count($this->media->$ns_class)) {
                    foreach ($this->media->$ns_class as $ns_record) {
                        if ($ns_record['element'] == $element) {
                            $value = htmlentities($ns_record['value'], ENT_QUOTES);
                            break;
                        }
                    }
                }
                $label = ucwords($element);
                echo "<li><label for='{$class}_{$element}' class='element'>$label</label><div class='element'>
                <input name='{$ns_class}[{$count}][element]' type='hidden' value='$element' />
                <input id='{$class}_{$element}' name='{$ns_class}[{$count}][value]' type='text' value='$value' size='55' /></div></li>";
                $count++;
            }
            ?>
            <li><label for="<?php echo $class; ?>_submit" class="element">&nbsp;</label><div class="element"><input id="<?php echo $class; ?>_submit" name="submit" value="Save" type="submit" /></div></li>
        </ol>
    </fieldset>
    <?php }
    
    ?>
    <a class="continue" id="continue3" href="#" onclick="document.getElementById('submit_existing').click();">Publish</a>
</div>
</form>