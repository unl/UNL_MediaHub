<?php

$jquery = '';
if (!isset($this->media)) {
    $jquery .= '
    //$("#part2").hide();
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
    if (isset($_GET['feed_id'])) {
        $jquery .= '$("#feedlist").hide();';
    }
} else {
    $jquery .= '$("#part1").hide();$("#feedlist").hide();$("#part2").show(400);';
}

$jquery .= '
    $("#continue2").click(function() {
            unl_check = /^http:\/\/([\w\d]+)\.unl\.edu\/(.*)/;
            var r = unl_check.exec(document.getElementById("url").value);
            if (r == null) {
                alert(\'Sorry, you must use a .unl.edu URL!\');
                return false;
            }
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
    <ul id="tabnav">
    	<li class="selected"><a href="#" id="a_url">Add by URL (http://&hellip;)</a></li>
    	<!-- <li><a href="#" id="a_file">Upload a file</a></li> -->
    </ul>
    <div id="formContent">
	    <input id="url" name="url" type="text" value="<?php echo htmlentities(@$this->media->url, ENT_QUOTES); ?>" />
	    <!-- <input id="file_upload" name="file_upload" type="file" /> -->
    	<input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="feed_media" />
        <input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="67108864" />
        <?php
        if (isset($this->media->id)) {
            echo '<input type="hidden" id="id" name="id" value="'.$this->media->id.'" />';
        }
        ?>
    </div>
    <p class="caption">media types supported: .m4v, .mp4, .mp3</p>
    <p class="submit"><a id="continue2" href="#">Continue</a></p>
    <div id="part1_close"></div>
</div>
<div id="part2" style="display:none;">
<div class="headline_main">
	<h1><?php echo (isset($this->media))?'Edit the details of your':'Tell us about your'; ?> media.</h1>
	<?php
	$thumbnail = 'templates/images/thumbs/placeholder.jpg';
	if (isset($this->media)) {
	    $thumbnail = UNL_MediaYak_Controller::$thumbnail_generator.urlencode($this->media->url);
	}
	?>
	<img src="<?php echo $thumbnail; ?>" id="thumbnail" alt="Thumbnail preview2" />
</div>
    <fieldset id="existing_media">
        <legend>Required Information</legend>
        <ol>
            <li><label for="title" class="element">Title</label><div class="element"><input id="title" name="title" type="text" size="60" value="<?php echo htmlentities(@$this->media->title, ENT_QUOTES); ?>" /></div></li>
            <li><label for="author" class="element">Author</label><div class="element"><input id="author" name="author" type="text" size="60" value="<?php echo htmlentities(@$this->media->author, ENT_QUOTES); ?>" /></div></li>
            <li>
                <label for="description" class="element">Description</label>
                <div class="element" id="description_wrapper"><textarea id="description" name="description" rows="5" cols="60"><?php echo htmlentities(@$this->media->description); ?></textarea></div>
            </li>

            <li style="display:none;"><label for="submit_existing" class="element">&nbsp;</label><div class="element"><input id="submit_existing" name="submit_existing" value="Save" type="submit" /></div></li>
        </ol>
    </fieldset>
    <?php
    function getFieldValue($savant, $xmlns, $element)
    {
        if (isset($savant->media)) {
            $class = 'UNL_MediaYak_Feed_Media_NamespacedElements_'.$xmlns;
            if ($element = call_user_func($class .'::mediaHasElement', $savant->media->id, $element, $xmlns)) {
                return htmlentities($element->value, ENT_QUOTES);
            }
        }
        return '';
    }
    function getFieldAttributes($savant, $xmlns, $element)
    {
        if (isset($savant->media)) {
            $class = 'UNL_MediaYak_Feed_Media_NamespacedElements_'.$xmlns;
            if ($element = call_user_func($class .'::mediaHasElement', $savant->media->id, $element, $xmlns)) {
                return htmlentities(serialize($element->attributes), ENT_QUOTES);
            }
        }
        return '';
    }
    ?>
    <fieldset id="enhanced_header">
        <legend>Enhanced Information</legend>
        <ol>
            <li style="display:none;">
                <label for="itunes_author" class="element">Author</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[0][element]" type="hidden" value="author"/>
                    <input id="itunes_author" name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[0][value]" type="text" value="<?php echo getFieldValue($this, 'itunes', 'author'); ?>" size="55"/>
                    
                <div class="form-help">
                    	<a href="#" class="imagelink" title="Get more information"><img src="templates/css/images/iconInfo.png" alt="Get More info on the Author attribute" /></a>
                    	<div class="help-content">
                    		<span class="help-pointer">&nbsp;</span>
                    		<p>This is the text for the form help</p>
                    	</div>
                    </div>
                </div>
            </li>
            <li>
                <label for="itunes_block" class="element">Block from iTunes</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[1][element]" type="hidden" value="block"/>
                    <select id="itunes_block" name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[1][value]">
                    	<?php
                    	if (getFieldValue($this, 'itunes', 'block') == "yes") {
                    		echo '<option value="">No</option><option value="yes" selected="selected">Yes</option>';
                    	} else {
                    		echo '<option value="">No</option><option value="yes">Yes</option>';
                    	}
                    	?>
                    </select>
                    <dl class="caption"><dd>Set to 'yes' if you would like to block this element from iTunes</dd></dl>
                </div>
            </li>
            <li>
                <label for="itunes_duration" class="element">Duration (HH:MM:SS)</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[2][element]" type="hidden" value="duration"/>
                    <input id="itunes_duration" name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[2][value]" type="text" value="<?php echo getFieldValue($this, 'itunes', 'duration'); ?>" size="55"/>
                </div>
            </li>
            <li style="display:none;">
                <label for="itunes_explicit" class="element">Explicit</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[3][element]" type="hidden" value="explicit"/>
                    <input id="itunes_explicit" name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[3][value]" type="text" value="<?php echo getFieldValue($this, 'itunes', 'explicit'); ?>" size="55"/>
                </div>
            </li>
            <li>
                <label for="itunes_keywords" class="element">Keywords</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[4][element]" type="hidden" value="keywords"/>
                    <input id="itunes_keywords" name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[4][value]" type="text" value="<?php echo getFieldValue($this, 'itunes', 'keywords'); ?>" size="55"/>
                    <dl class="caption"><dd>A comma separated list of keywords, MAX 10</dd></dl>
                </div>
            </li>
            <li>
                <label for="itunes_subtitle" class="element">Subtitle</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[5][element]" type="hidden" value="subtitle"/>
                    <input id="itunes_subtitle" name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[5][value]" type="text" value="<?php echo getFieldValue($this, 'itunes', 'subtitle'); ?>" size="55"/>
                </div>
            </li>
            <li>
                <label for="itunes_summary" class="element">Summary</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[6][element]" type="hidden" value="summary"/>
                    <input id="itunes_summary" name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[6][value]" type="text" value="<?php echo getFieldValue($this, 'itunes', 'summary'); ?>" size="55"/>
                </div>
            </li>
            <li style="display:none;">
                <label for="mrss_group" class="element">Group</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[0][element]" type="hidden" value="group"/>
                    <input id="mrss_group" name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[0][value]" type="text" value="<?php echo getFieldValue($this, 'mrss', 'group'); ?>" size="55"/>
                </div>
            </li>
            <li style="display:none">
                <!-- mrss hidden elements that are handled automatically -->
                <input name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[1][media_id]" type="hidden" value="<?php echo $this->media->id; ?>"/>
                <input name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[1][element]" type="hidden" value="content" />
                <input name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[1][value]" type="hidden" value="<?php echo getFieldValue($this, 'mrss', 'content'); ?>" />
                <input name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[1][attributes]" type="hidden" value="<?php echo getFieldAttributes($this, 'mrss', 'content'); ?>" />
            </li>
            <li style="display:none;">
                <label for="mrss_rating" class="element">Rating</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[2][element]" type="hidden" value="rating"/>
                    <input id="mrss_rating" name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[2][value]" type="text" value="<?php echo getFieldValue($this, 'mrss', 'rating'); ?>" size="55"/>
                </div>
            </li>
            <li style="display:none;">
                <label for="mrss_title" class="element">Title</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[3][element]" type="hidden" value="title"/>
                    <input id="mrss_title" name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[3][value]" type="text" value="<?php echo getFieldValue($this, 'mrss', 'title'); ?>" size="55"/>
                </div>
            </li>
            <li style="display:none;">
                <label for="mrss_description" class="element">Description</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[4][element]" type="hidden" value="description"/>
                    <input id="mrss_description" name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[4][value]" type="text" value="<?php echo getFieldValue($this, 'mrss', 'description'); ?>" size="55"/>
                </div>
            </li>
            <li style="display:none;">
                <label for="mrss_keywords" class="element">Keywords</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[5][element]" type="hidden" value="keywords"/>
                    <input id="mrss_keywords" name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[5][value]" type="text" value="<?php echo getFieldValue($this, 'mrss', 'keywords'); ?>" size="55"/>
                </div>
            </li>
            <li style="display:none;">
                <!-- mrss hidden elements that are handled automatically -->
                <input name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[6][media_id]" type="hidden" value="<?php echo $this->media->id; ?>"/>
                <input name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[6][element]" type="hidden" value="thumbnail"/>
                <input name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[6][value]" type="hidden" value="<?php echo getFieldValue($this, 'mrss', 'thumbnail'); ?>" />
                <input name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[6][attributes]" type="hidden" value="<?php echo getFieldAttributes($this, 'mrss', 'thumbnail'); ?>" />
            </li>
            <li>
                <label for="mrss_category" class="element">Category</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[7][element]" type="hidden" value="category"/>
                    <input id="mrss_category" name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[7][value]" type="text" value="<?php echo getFieldValue($this, 'mrss', 'category'); ?>" size="55"/>
                </div>
            </li>
            <li style="display:none;">
                <label for="mrss_player" class="element">Player</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[8][element]" type="hidden" value="player"/>
                    <input id="mrss_player" name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[8][value]" type="text" value="<?php echo getFieldValue($this, 'mrss', 'player'); ?>" size="55"/>
                </div>
            </li>
            <li>
                <label for="mrss_credit" class="element">Credit</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[9][element]" type="hidden" value="credit"/>
                    <input id="mrss_credit" name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[9][value]" type="text" value="<?php echo getFieldValue($this, 'mrss', 'credit'); ?>" size="55"/>
                </div>
            </li>
            <li>
                <label for="mrss_copyright" class="element">Copyright</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[10][element]" type="hidden" value="copyright"/>
                    <input id="mrss_copyright" name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[10][value]" type="text" value="<?php echo getFieldValue($this, 'mrss', 'copyright'); ?>" size="55"/>
                </div>
            </li>
            <li>
                <label for="mrss_text" class="element">Transcript/Captioning</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[11][element]" type="hidden" value="text"/>
                    <textarea rows="3" cols="50" id="mrss_text" name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[11][value]"><?php echo getFieldValue($this, 'mrss', 'text'); ?></textarea>
                </div>
            </li>
            <li style="display:none;">
                <label for="mrss_restriction" class="element">Restriction</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[12][element]" type="hidden" value="restriction"/>
                    <input id="mrss_restriction" name="UNL_MediaYak_Feed_Media_NamespacedElements_mrss[12][value]" type="text" value="<?php echo getFieldValue($this, 'mrss', 'restriction'); ?>" size="55"/>
                </div>
            </li>
        </ol>
    </fieldset>
    <fieldset>
        <legend>Select Your Feeds</legend>
        <ol>
            <?php
            foreach (UNL_MediaYak_Manager::getUser()->getFeeds()->items as $feed) {
                $checked = '';
                if ((isset($this->media) && ($feed->hasMedia($this->media))
                    || (isset($_GET['feed_id']) && $_GET['feed_id'] == $feed->id))) {
                    $checked = 'checked="checked"';
                }
                echo '<li><label for="feed_id['.$feed->id.']" class="element">'.$feed->title.'</label><div class="element"><input id="feed_id['.$feed->id.']" name="feed_id['.$feed->id.']" type="checkbox" '.$checked.' /></div></li>';
            }
            ?>
            <li><label for="new_feed" class="element">New Feed</label><div class="element"><input id="new_feed" name="new_feed" type="text" /></div></li>
        </ol>
    </fieldset>
    <p class="submit"><a class="continue"  id="continue3" href="#" onclick="document.getElementById('submit_existing').click();">Publish</a></p>
</div>
</form>