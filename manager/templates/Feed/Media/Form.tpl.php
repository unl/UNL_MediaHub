<?php

$jquery = '';
if (!isset($this->media)) {
    $jquery .= '
    $("#part2").hide();
    $("#file_upload").hide();
    $("#a_url").click(function(){
    	$(this).parent("li").addClass("selected");
    	$(this).parent("li").siblings("li").removeClass("selected");
    	$("#file_upload").hide(400);
    	$("#url").show(400);
    	
    	return false;
    });
    $("#a_file").click(function(){
    	$(this).parent("li").addClass("selected");
    	$(this).parent("li").siblings("li").removeClass("selected");
    	$("#url").hide(400);
    	$("#file_upload").show(400);
    	return false;
    });
    ';
} else {
    $jquery .= '$("#part1").hide();$("#feedlist").hide();';
}

$jquery .= '
    $("#continue2").click(function() {
            $("#feedlist").hide(400);
            $("#part1").hide(400);
            $("#part2").show(400);
            document.getElementById("thumbnail").src = "'.UNL_MediaYak_Controller::$thumbnail_generator.'"+document.getElementById("url").value;
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
<style type="text/css">

</style>
<!-- Skin CSS file -->
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.6.0/build/assets/skins/sam/skin.css" />
<!-- Utility Dependencies -->
<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/yahoo-dom-event/yahoo-dom-event.js"></script> 
<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/element/element-beta-min.js"></script> 
<!-- Needed for Menus, Buttons and Overlays used in the Toolbar -->
<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/container/container_core-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/menu/menu-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/button/button-min.js"></script>
<!-- Source file for Rich Text Editor-->
<script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/editor/editor-min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    '.$jquery.'
});
var myEditor = new YAHOO.widget.Editor("description", { 
    height: "300px", 
    width: "522px", 
    dompath: true, //Turns on the bar at the bottom 
    animate: true, //Animates the opening, closing and moving of Editor windows
    handleSubmit: true 
    }); 
    myEditor.render();

</script>

');

?>
<form action="?view=feed" method="post" name="media_form" id="media_form" enctype="multipart/form-data"  class="yui-skin-sam">
<div id="part1">
    <h1>Add new media:</h1>
    <div style="display: none;">
        <input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="feed_media" />
        <input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="67108864" />
        <?php
        if (isset($this->media->id)) {
            echo '<input type="hidden" id="id" name="id" value="'.$this->media->id.'" />';
        }
        ?>
    </div>
    
    <ul id="tabnav">
    	<li class="selected"><a href="#" id="a_url">Add by URL</a></li>
    	<li><a href="#" id="a_file">Upload a file</a></li>
    </ul>
    <div id="formContent">
	    <input id="url" name="url" type="text" value="<?php echo htmlentities(@$this->media->url, ENT_QUOTES); ?>" />
    	<input id="file_upload" name="file_upload" type="file" />
    </div>
    <p class="caption">media types supported: .m4v, .mp4, .mp3</p>
    <p class="submit"><a id="continue2" href="#">Continue</a></p>
    <div id="part1_close"></div>
</div>
<div id="part2">
<h1><?php echo (isset($this->media))?'Edit the details of your':'Tell us about your'; ?> media.</h1>
<div><img src="<?php echo UNL_MediaYak_Controller::$thumbnail_generator.urlencode($this->media->url); ?>" id="thumbnail" alt="Thumbnail preview" /></div>
    <fieldset id="existing_media">
        <legend>Existing Media</legend>
        <ol>
            <li><label for="title" class="element">Title</label><div class="element"><input id="title" name="title" type="text" size="60" value="<?php echo htmlentities(@$this->media->title, ENT_QUOTES); ?>" /></div></li>
            <li>
                <label for="description" class="element">Description</label>
                <div class="element" id="description_wrapper"><textarea id="description" name="description" rows="5" cols="60"><?php echo htmlentities(@$this->media->description); ?></textarea></div>
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
    <?php } ?>
    <fieldset>
        <legend>Add to my feeds</legend>
        <ol>
            <?php
            foreach (UNL_MediaYak_Manager::getUser()->getFeeds()->items as $feed) {
                $checked = '';
                if (isset($this->media)
                    && $feed->hasMedia($this->media)) {
                    $checked = 'checked="checked"';
                }
                echo '<li><label for="feed_id['.$feed->id.']" class="element">'.$feed->title.'</label><div class="element"><input id="feed_id['.$feed->id.']" name="feed_id['.$feed->id.']" type="checkbox" '.$checked.' /></div></li>';
            }
            ?>
            <li><label for="new_feed" class="element">New Feed</label><div class="element"><input id="new_feed" id="new_feed" type="text" /></div></li>
        </ol>
    </fieldset>
    <a class="continue" id="continue3" href="#" onclick="document.getElementById('submit_existing').click();">Publish</a>
</div>
</form>