<?php

$jquery = '';
if (!isset($context->media)) {
    $jquery .= '
    //WDN.jQuery("#part2").hide();
    WDN.jQuery("#file_upload").hide();
    WDN.jQuery("#a_url").click(function(){
    	WDN.jQuery(this).parent("li").addClass("selected");
    	WDN.jQuery(this).parent("li").siblings("li").removeClass("selected");
    	WDN.jQuery("#file_upload").hide(400);
    	WDN.jQuery("#url").show(400);
    	
    	return false;
    });
    WDN.jQuery("#a_file").click(function(){
    	WDN.jQuery(this).parent("li").addClass("selected");
    	WDN.jQuery(this).parent("li").siblings("li").removeClass("selected");
    	WDN.jQuery("#url").hide(400);
    	WDN.jQuery("#file_upload").show(400);
    	return false;
    });
    ';
    if (isset($_GET['feed_id'])) {
        $jquery .= 'WDN.jQuery("#feedlist").hide();';
    }
} else {
    $jquery .= 'WDN.jQuery("#part1").hide();WDN.jQuery("#feedlist").hide();WDN.jQuery("#part2").show(400);';
}

$jquery .= '
    WDN.jQuery("#continue2").click(function() {
            unl_check = /^http:\/\/([^\/]+)\.unl\.edu\/(.*)/;
            var r = unl_check.exec(document.getElementById("url").value);
            if (r == null) {
                alert(\'Sorry, you must use a .unl.edu URL!\');
                return false;
            }
            WDN.jQuery("#feedlist").hide(400);
            WDN.jQuery("#part1").hide(400);
            WDN.jQuery("#part2").show(400);
            document.getElementById("thumbnail").src = "'.UNL_MediaYak_Controller::$thumbnail_generator.'"+document.getElementById("url").value;
            return false;
        }
    );';
$jquery .= '
    WDN.jQuery("#itunes_header ol").hide();
    WDN.jQuery("#mrss_header ol").hide();
    
    WDN.jQuery("#itunes_header legend").click(function() {
      WDN.jQuery("#itunes_header ol").toggle(400);
      return false;
    });
    WDN.jQuery("#mrss_header legend").click(function() {
      WDN.jQuery("#mrss_header ol").toggle(400);
      return false;
    });';
$jquery .= '
	WDN.loadJS("templates/scripts/tiny_mce/jquery.tinymce.js", function() {
		WDN.jQuery("textarea#description").tinymce({
				// Location of TinyMCE script
				script_url : "templates/scripts/tiny_mce/tiny_mce.js",
				theme : "simple"
		});
	});
';

UNL_MediaYak_Manager::setReplacementData('head','

<script type="text/javascript">
WDN.jQuery(document).ready(function() {
    '.$jquery.'
});
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
	    <input id="url" name="url" type="text" value="<?php echo htmlentities(@$context->media->url, ENT_QUOTES); ?>" />
	    <!-- <input id="file_upload" name="file_upload" type="file" /> -->
    	<input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="feed_media" />
        <input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="67108864" />
        <?php
        if (isset($context->media->id)) {
            echo '<input type="hidden" id="id" name="id" value="'.$context->media->id.'" />';
        }
        ?>
    </div>
    <p class="caption">media types supported: .m4v, .mp4, .mp3</p>
    <p class="submit"><a id="continue2" href="#">Continue</a></p>
    <div id="part1_close"></div>
</div>
<div id="part2" style="display:none;">
<div class="headline_main">
	<h1><?php echo (isset($context->media))?'Edit the details of your':'Tell us about your'; ?> media.</h1>
	<?php
	$thumbnail = 'templates/images/thumbs/placeholder.jpg';
	if (isset($context->media)) {
	    $thumbnail = UNL_MediaYak_Controller::$thumbnail_generator.urlencode($context->media->url);
	}
	?>
	<img src="<?php echo $thumbnail; ?>" id="thumbnail" alt="Thumbnail preview2" />
</div>
    <fieldset id="existing_media">
        <legend>Required Information</legend>
        <ol>
            <li><label for="title" class="element">Title</label><div class="element"><input id="title" name="title" type="text" size="60" value="<?php echo htmlentities(@$context->media->title, ENT_QUOTES); ?>" /></div></li>
            <li><label for="author" class="element">Author</label><div class="element"><input id="author" name="author" type="text" size="60" value="<?php echo htmlentities(@$context->media->author, ENT_QUOTES); ?>" /></div></li>
            <li>
                <label for="description" class="element">Description</label>
                <div class="element" id="description_wrapper"><textarea id="description" name="description" rows="5" cols="60"><?php echo htmlentities(@$context->media->description); ?></textarea></div>
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
                    <input id="itunes_author" name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[0][value]" type="text" value="<?php echo getFieldValue($context, 'itunes', 'author'); ?>" size="55"/>
                    
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
                <label for="itunesu_category" class="element">Category</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_itunesu[0][element]" type="hidden" value="category" />
                    <select id="itunes_block" name="UNL_MediaYak_Feed_Media_NamespacedElements_itunesu[0][attributes]">
                        <option value="">None</option>
                        <optgroup label="Business">
                            <option value="100">Business</option>
                            <option value="100100">Economics</option>
                            <option value="100101">Finance</option>
                            <option value="100102">Hospitality</option>
                            <option value="100103">Management</option>
                            <option value="100104">Marketing</option>
                            <option value="100105">Personal Finance</option>
                            <option value="100106">Real Estate</option>
                        </optgroup>
                        <optgroup label="Engineering">
                            <option value="101">Engineering</option>
                            <option value="101100">Chemical & Petroleum</option>
                            <option value="101101">Civil</option>
                            <option value="101102">Computer Science</option>
                            <option value="101103">Electrical</option>
                            <option value="101104">Environmental</option>
                            <option value="101105">Mechanical</option>
                        </optgroup>
                        <optgroup label="Fine Arts">
                            <option value="102">Fine Arts</option>
                            <option value="102100">Architecture</option>
                            <option value="102101">Art</option>
                            <option value="102102">Art History</option>
                            <option value="102103">Dance</option>
                            <option value="102104">Film</option>
                            <option value="102105">Graphic Design</option>
                            <option value="102106">Interior Design</option>
                            <option value="102107">Music</option>
                            <option value="102108">Theater</option>
                        </optgroup>
                        <optgroup label="Health & Medicine">
                            <option value="103">Health & Medicine</option>
                            <option value="103100">Anatomy & Physiology</option>
                            <option value="103101">Behavioral Science</option>
                            <option value="103102">Dentistry</option>
                            <option value="103103">Diet & Nutrition</option>
                            <option value="103104">Emergency</option>
                            <option value="103105">Genetics</option>
                            <option value="103106">Gerontology</option>
                            <option value="103107">Health & Exercise Science</option>
                            <option value="103108">Immunology</option>
                            <option value="103109">Neuroscience</option>
                            <option value="103110">Pharmacology & Toxicology</option>
                            <option value="103111">Psychiatry</option>
                            <option value="103112">Public Health</option>
                            <option value="103113">Radiology</option>
                        </optgroup>
                        <optgroup label="History">
                            <option value="104">History</option>
                            <option value="104100">Ancient</option>
                            <option value="104101">Medieval</option>
                            <option value="104102">Military</option>
                            <option value="104103">Modern</option>
                            <option value="104104">African</option>
                            <option value="104105">Asian</option>
                            <option value="104106">European</option>
                            <option value="104107">Middle Eastern</option>
                            <option value="104108">North American</option>
                            <option value="104109">South American</option>
                        </optgroup>
                        <optgroup label="Humanities">
                            <option value="105">Humanities</option>
                            <option value="105100">Communications</option>
                            <option value="105101">Philosophy</option>
                            <option value="105102">Religion</option>
                        </optgroup>
                        <optgroup label="Language">
                            <option value="106">Language</option>
                            <option value="106100">African</option>
                            <option value="106101">Ancient</option>
                            <option value="106102">Asian</option>
                            <option value="106103">Eastern European/Slavic</option>
                            <option value="106104">English</option>
                            <option value="106105">English Language Learners</option>
                            <option value="106106">French</option>
                            <option value="106107">German</option>
                            <option value="106108">Italian</option>
                            <option value="106109">Linguistics</option>
                            <option value="106110">Middle Eastern</option>
                            <option value="106111">Spanish & Portuguese</option>
                            <option value="106112">Speech Pathology</option>
                        </optgroup>
                        <optgroup label="Literature">
                            <option value="107">Literature</option>
                            <option value="107100">Anthologies</option>
                            <option value="107101">Biography</option>
                            <option value="107102">Classics</option>
                            <option value="107103">Criticism</option>
                            <option value="107104">Fiction</option>
                            <option value="107105">Poetry</option>
                        </optgroup>
                        <optgroup label="Mathematics">
                            <option value="108">Mathematics</option>
                            <option value="108100">Advanced Mathematics</option>
                            <option value="108101">Algebra</option>
                            <option value="108102">Arithmetic</option>
                            <option value="108103">Calculus</option>
                            <option value="108104">Geometry</option>
                            <option value="108105">Statistics</option>
                        </optgroup>
                        <optgroup label="Science">
                            <option value="109">Science</option>
                            <option value="109100">Agricultural</option>
                            <option value="109101">Astronomy</option>
                            <option value="109102">Atmospheric</option>
                            <option value="109103">Biology</option>
                            <option value="109104">Chemistry</option>
                            <option value="109105">Ecology</option>
                            <option value="109106">Geography</option>
                            <option value="109107">Geology</option>
                            <option value="109108">Physics</option>
                        </optgroup>
                        <optgroup label="Social Science">
                            <option value="110">Social Science</option>
                            <option value="110100">Law</option>
                            <option value="110101">Political Science</option>
                            <option value="110102">Public Administration</option>
                            <option value="110103">Psychology</option>
                            <option value="110104">Social Welfare</option>
                            <option value="110105">Sociology</option>
                        </optgroup>
                        <optgroup label="Society">
                            <option value="111">Society</option>
                            <option value="111100">African-American Studies</option>
                            <option value="111101">Asian Studies</option>
                            <option value="111102">European & Russian Studies</option>
                            <option value="111103">Indigenous Studies</option>
                            <option value="111104">Latin & Caribbean Studies</option>
                            <option value="111105">Middle Eastern Studies</option>
                            <option value="111106">Women's Studies</option>
                        </optgroup>
                        <optgroup label="Teaching & Education">
                            <option value="112">Teaching & Education</option>
                            <option value="112100">Curriculum & Teaching</option>
                            <option value="112101">Educational Leadership</option>
                            <option value="112102">Family & Childcare</option>
                            <option value="112103">Learning Resources</option>
                            <option value="112104">Psychology & Research</option>
                            <option value="112105">Special Education
                        </optgroup>
                    </select>
                    <dl class="caption"><dd>Choose a category for use within iTunes U</dd></dl>
                </div>
            </li>
            <li>
                <label for="itunes_block" class="element">Block from iTunes</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[1][element]" type="hidden" value="block"/>
                    <select id="itunes_block" name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[1][value]">
                    	<?php
                    	if (getFieldValue($context, 'itunes', 'block') == "yes") {
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
                    <input id="itunes_duration" name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[2][value]" type="text" value="<?php echo getFieldValue($context, 'itunes', 'duration'); ?>" size="55"/>
                </div>
            </li>
            <li style="display:none;">
                <label for="itunes_explicit" class="element">Explicit</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[3][element]" type="hidden" value="explicit"/>
                    <input id="itunes_explicit" name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[3][value]" type="text" value="<?php echo getFieldValue($context, 'itunes', 'explicit'); ?>" size="55"/>
                </div>
            </li>
            <li>
                <label for="itunes_keywords" class="element">Keywords</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[4][element]" type="hidden" value="keywords"/>
                    <input id="itunes_keywords" name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[4][value]" type="text" value="<?php echo getFieldValue($context, 'itunes', 'keywords'); ?>" size="55"/>
                    <dl class="caption"><dd>A comma separated list of keywords, MAX 10</dd></dl>
                </div>
            </li>
            <li>
                <label for="itunes_subtitle" class="element">Subtitle</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[5][element]" type="hidden" value="subtitle"/>
                    <input id="itunes_subtitle" name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[5][value]" type="text" value="<?php echo getFieldValue($context, 'itunes', 'subtitle'); ?>" size="55"/>
                </div>
            </li>
            <li>
                <label for="itunes_summary" class="element">Summary</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[6][element]" type="hidden" value="summary"/>
                    <input id="itunes_summary" name="UNL_MediaYak_Feed_Media_NamespacedElements_itunes[6][value]" type="text" value="<?php echo getFieldValue($context, 'itunes', 'summary'); ?>" size="55"/>
                </div>
            </li>
            <li style="display:none;">
                <label for="mrss_group" class="element">Group</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_media[0][element]" type="hidden" value="group"/>
                    <input id="mrss_group" name="UNL_MediaYak_Feed_Media_NamespacedElements_media[0][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'group'); ?>" size="55"/>
                </div>
            </li>
            <li style="display:none">
                <!-- mrss hidden elements that are handled automatically -->
                <input name="UNL_MediaYak_Feed_Media_NamespacedElements_media[1][media_id]" type="hidden" value="<?php echo $context->media->id; ?>"/>
                <input name="UNL_MediaYak_Feed_Media_NamespacedElements_media[1][element]" type="hidden" value="content" />
                <input name="UNL_MediaYak_Feed_Media_NamespacedElements_media[1][value]" type="hidden" value="<?php echo getFieldValue($context, 'media', 'content'); ?>" />
                <input name="UNL_MediaYak_Feed_Media_NamespacedElements_media[1][attributes]" type="hidden" value="<?php echo getFieldAttributes($context, 'media', 'content'); ?>" />
            </li>
            <li style="display:none;">
                <label for="mrss_rating" class="element">Rating</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_media[2][element]" type="hidden" value="rating"/>
                    <input id="mrss_rating" name="UNL_MediaYak_Feed_Media_NamespacedElements_media[2][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'rating'); ?>" size="55"/>
                </div>
            </li>
            <li style="display:none;">
                <label for="mrss_title" class="element">Title</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_media[3][element]" type="hidden" value="title"/>
                    <input id="mrss_title" name="UNL_MediaYak_Feed_Media_NamespacedElements_media[3][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'title'); ?>" size="55"/>
                </div>
            </li>
            <li style="display:none;">
                <label for="mrss_description" class="element">Description</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_media[4][element]" type="hidden" value="description"/>
                    <input id="mrss_description" name="UNL_MediaYak_Feed_Media_NamespacedElements_media[4][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'description'); ?>" size="55"/>
                </div>
            </li>
            <li style="display:none;">
                <label for="mrss_keywords" class="element">Keywords</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_media[5][element]" type="hidden" value="keywords"/>
                    <input id="mrss_keywords" name="UNL_MediaYak_Feed_Media_NamespacedElements_media[5][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'keywords'); ?>" size="55"/>
                </div>
            </li>
            <li style="display:none;">
                <!-- mrss hidden elements that are handled automatically -->
                <input name="UNL_MediaYak_Feed_Media_NamespacedElements_media[6][media_id]" type="hidden" value="<?php echo $context->media->id; ?>"/>
                <input name="UNL_MediaYak_Feed_Media_NamespacedElements_media[6][element]" type="hidden" value="thumbnail"/>
                <input name="UNL_MediaYak_Feed_Media_NamespacedElements_media[6][value]" type="hidden" value="<?php echo getFieldValue($context, 'media', 'thumbnail'); ?>" />
                <input name="UNL_MediaYak_Feed_Media_NamespacedElements_media[6][attributes]" type="hidden" value="<?php echo getFieldAttributes($context, 'media', 'thumbnail'); ?>" />
            </li>
            <li>
                <label for="mrss_category" class="element">Category</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_media[7][element]" type="hidden" value="category"/>
                    <input id="mrss_category" name="UNL_MediaYak_Feed_Media_NamespacedElements_media[7][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'category'); ?>" size="55"/>
                </div>
            </li>
            <li style="display:none;">
                <label for="mrss_player" class="element">Player</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_media[8][element]" type="hidden" value="player"/>
                    <input id="mrss_player" name="UNL_MediaYak_Feed_Media_NamespacedElements_media[8][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'player'); ?>" size="55"/>
                </div>
            </li>
            <li>
                <label for="mrss_credit" class="element">Credit</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_media[9][element]" type="hidden" value="credit"/>
                    <input id="mrss_credit" name="UNL_MediaYak_Feed_Media_NamespacedElements_media[9][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'credit'); ?>" size="55"/>
                </div>
            </li>
            <li>
                <label for="mrss_copyright" class="element">Copyright</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_media[10][element]" type="hidden" value="copyright"/>
                    <input id="mrss_copyright" name="UNL_MediaYak_Feed_Media_NamespacedElements_media[10][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'copyright'); ?>" size="55"/>
                </div>
            </li>
            <li>
                <label for="mrss_text" class="element">Transcript/Captioning</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_media[11][element]" type="hidden" value="text"/>
                    <textarea rows="3" cols="50" id="mrss_text" name="UNL_MediaYak_Feed_Media_NamespacedElements_media[11][value]"><?php echo getFieldValue($context, 'media', 'text'); ?></textarea>
                </div>
            </li>
            <li style="display:none;">
                <label for="mrss_restriction" class="element">Restriction</label>
                <div class="element">
                    <input name="UNL_MediaYak_Feed_Media_NamespacedElements_media[12][element]" type="hidden" value="restriction"/>
                    <input id="mrss_restriction" name="UNL_MediaYak_Feed_Media_NamespacedElements_media[12][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'restriction'); ?>" size="55"/>
                </div>
            </li>
        </ol>
    </fieldset>
    <fieldset>
        <legend>Select Your Feeds</legend>
        <ol>
            <?php
            $list = UNL_MediaYak_Manager::getUser()->getFeeds();
            UNL_MediaYak_OutputController::setOutputTemplate('UNL_MediaYak_FeedList', 'Feed_Media_FeedList');
            echo $savvy->render($list);
            UNL_MediaYak_OutputController::setOutputTemplate('UNL_MediaYak_FeedList', 'FeedList');
            ?>
            <li><label for="new_feed" class="element">New Feed</label><div class="element"><input id="new_feed" name="new_feed" type="text" /></div></li>
        </ol>
    </fieldset>
    <p class="submit"><a class="continue"  id="continue3" href="#" onclick="document.getElementById('submit_existing').click();">Publish</a></p>
</div>
</form>
