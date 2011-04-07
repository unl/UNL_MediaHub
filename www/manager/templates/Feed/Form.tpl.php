<script type="text/javascript">
WDN.jQuery(document).ready(function() {
    WDN.jQuery('#extensions input').change(function() {
        if(this.checked){
            WDN.jQuery('#'+this.value+'_header').slideDown(400);
        } else {
            WDN.jQuery('#'+this.value+'_header').slideUp(400);
        }
        return true;
    });
    WDN.jQuery('#description').change(function(){
		if (WDN.jQuery('#itunes_summary').val() == '') {
			WDN.jQuery('#itunes_summary').val(WDN.jQuery(this).val());
		}
		if (WDN.jQuery('#media_description').val() == '') {
			WDN.jQuery('#media_description').val(WDN.jQuery(this).val());
		}
    });
    WDN.jQuery('#title').change(function(){
		if (WDN.jQuery('#media_title').val() == '') {
			WDN.jQuery('#media_title').val(WDN.jQuery(this).val());
		}
    });
});
</script>
<div class="three_col left">
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

        <legend><?php echo (isset($context->feed))?'Edit':'Create'; ?> a Channel</legend>
        <ol>
            <li>
                <label for="title"><span class="required">*</span>Title <span class="helper">Channel name displayed everywhere.</span></label>
                <input id="title" name="title" type="text" value="<?php echo (isset($context->feed))? htmlentities($context->feed->title, ENT_QUOTES):''; ?>" size="55" />
            </li>
            <li>
                <label for="description"><span class="required">*</span>Description<span class="helper">Explain what this channel is all about. Use a few sentences, but keep it to 1 paragraph.</span></label>
                <textarea id="description" name="description" rows="5" cols="50"><?php echo (isset($context->feed))?htmlentities($context->feed->description):''; ?></textarea>
            </li>
            <li>
                <fieldset>
                    <legend>Consider this channel for: <span class="helper">Delivery options, coming soon!</span></legend>
                    <ol id="extensions">
                        <li>
                            <input type="checkbox" value="itunes" id="iTunesConsideration" name="iTunesConsideration" />
                            <label for="iTunesConsideration">iTunes U</label>
                        </li>
                        <li>
                            <input type="checkbox" value="boxee" id="boxeeConsideration" name="boxeeConsideration" />
                            <label for="boxeeConsideration">Boxee</label>
                        </li>
                        <li>
                            <input type="checkbox" value="youtube" id="youTubeConsideration" name="youTubeConsideration" />
                            <label for="youTubeConsideration">YouTube</label>
                        </li>
                    </ol>
                </fieldset>
            </li>
        </ol>
        <legend>Channel Image</legend>
            <ol>
            <li>
                <label class="element"><span class="required">*</span>Image File<span class="helper">Images should follow the standard UNL image standards. <a href="http://ucommdev.unl.edu/iTunesU/design_files/icon_template.psd">(get the sample template)</a>
                </span></label>
                <input id="image_file" name="image_file" type="file" />
            </li>
            <li>
                <label for="image_title" class="element">Image Title<span class="helper">Give the image a title, used in RSS feeds.</span></label>
                <input id="image_title" name="image_title" type="text" value="<?php echo (isset($context->feed))? htmlentities($context->feed->image_title, ENT_QUOTES):''; ?>" size="55" />
            </li>
            <li>
                <label for="image_description" class="element">Image Description<span class="helper">Describe your image, used in RSS feeds.</span></label>
                <textarea id="image_description" name="image_description" rows="5" cols="50"><?php echo (isset($context->feed))?htmlentities($context->feed->image_description):''; ?></textarea>
            </li>
        </ol>
    </fieldset>
    <?php
    function getFieldValue($context, $xmlns, $element)
    {
        if (isset($context->feed)) {
            $class = 'UNL_MediaHub_Feed_NamespacedElements_'.$xmlns;
            if ($element = call_user_func($class .'::feedHasElement', $context->feed->id, $element, $xmlns)) {
                return htmlentities($element->value, ENT_QUOTES);
            }
        }
        return '';
    }
    function getFieldAttributes($context, $xmlns, $element)
    {
        if (isset($context->feed)) {
            $class = 'UNL_MediaHub_Feed_NamespacedElements_'.$xmlns;
            if ($element = call_user_func($class .'::feedHasElement', $context->feed->id, $element, $xmlns)) {
                return htmlentities(serialize($element->attributes), ENT_QUOTES);
            }
        }
        return '';
    }
    ?>
    <fieldset id="itunes_header" style="display:none">

        <legend>iTunes U Options</legend>

        <ol>
            <li><label for='itunes_author' class='element'>Author<span class="helper">Used in the Artist column of iTunes</span></label><div class='element'>
                <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[0][element]' type='hidden' value='author' />
                <input id='itunes_author' name='UNL_MediaHub_Feed_NamespacedElements_itunes[0][value]' type='text' value='<?php echo getFieldValue($context, 'itunes', 'author'); ?>' size='55' />
                </div></li>
            <li><label for='itunes_block' class='element'>Block<span class="helper">Keep this channel from appearing in iTunes</span></label><div class='element'>
                <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[1][element]' type='hidden' value='block' />
                <input id='itunes_block' name='UNL_MediaHub_Feed_NamespacedElements_itunes[1][value]' type='text' value='<?php echo getFieldValue($context, 'itunes', 'block'); ?>' size='55' />
                </div></li>
            <li>
                <label for='itunes_category' class='element'>Category <span class="helper">Select an appropriate category listing.</span></label>
                <div class='element'>
                    <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[2][element]' type='hidden' value='category' />
                    <?php
                    $categories = array();
                    if ($value = UNL_MediaHub_Feed_NamespacedElements_itunes::feedHasElement($context->feed->id, 'category', 'itunes')) {
                        $categories = $value['attributes']['text'];
                    }
                    ?>
                    <select id='itunes_category' name='UNL_MediaHub_Feed_NamespacedElements_itunes[2][attributes][]' multiple="multiple">
                        <option <?php if (in_array('Arts', $categories)) echo 'selected="selected"'; ?> value="Arts">Arts</option>
                        <optgroup label="">
                            <option <?php if (in_array('Arts:Design', $categories)) echo 'selected="selected"'; ?> value="Arts:Design">Design</option>
                            <option <?php if (in_array('Arts:Fashion & Beauty', $categories)) echo 'selected="selected"'; ?> value="Arts:Fashion &amp; Beauty">Fashion &amp; Beauty</option>
                            <option <?php if (in_array('Arts:Food', $categories)) echo 'selected="selected"'; ?> value="Arts:Food">Food</option>
                            <option <?php if (in_array('Arts:Literature', $categories)) echo 'selected="selected"'; ?> value="Arts:Literature">Literature</option>
                            <option <?php if (in_array('Arts:Performing Arts', $categories)) echo 'selected="selected"'; ?> value="Arts:Performing Arts">Performing Arts</option>
                            <option <?php if (in_array('Arts:Visual Arts', $categories)) echo 'selected="selected"'; ?> value="Arts:Visual Arts">Visual Arts</option>
                        </optgroup>
                        <option <?php if (in_array('Business', $categories)) echo 'selected="selected"'; ?> value="Business">Business</option>
                        <optgroup label="">
                            <option <?php if (in_array('Business:Business News', $categories)) echo 'selected="selected"'; ?> value="Business:Business News">Business News</option>
                            <option <?php if (in_array('Business:Careers', $categories)) echo 'selected="selected"'; ?> value="Business:Careers">Careers</option>
                            <option <?php if (in_array('Business:Investing', $categories)) echo 'selected="selected"'; ?> value="Business:Investing">Investing</option>
                            <option <?php if (in_array('Business:Management & Marketing', $categories)) echo 'selected="selected"'; ?> value="Business:Management &amp; Marketing">Management &amp; Marketing</option>
                            <option <?php if (in_array('Business:Shopping', $categories)) echo 'selected="selected"'; ?> value="Business:Shopping">Shopping</option>
                        </optgroup>
                        <option <?php if (in_array('Comedy', $categories)) echo 'selected="selected"'; ?> value="Comedy">Comedy</option>
                        <option <?php if (in_array('Education', $categories)) echo 'selected="selected"'; ?> value="Education">Education</option>
                        <optgroup label="">
                            <option <?php if (in_array('Education:Education Technology', $categories)) echo 'selected="selected"'; ?> value="Education:Education Technology">Education Technology</option>
                            <option <?php if (in_array('Education:Higher Education', $categories)) echo 'selected="selected"'; ?> value="Education:Higher Education">Higher Education</option>
                            <option <?php if (in_array('Education:K-12', $categories)) echo 'selected="selected"'; ?> value="Education:K-12">K-12</option>
                            <option <?php if (in_array('Education:Language Courses', $categories)) echo 'selected="selected"'; ?> value="Education:Language Courses">Language Courses</option>
                            <option <?php if (in_array('Education:Training', $categories)) echo 'selected="selected"'; ?> value="Education:Training">Training</option>
                        </optgroup>
                        <option <?php if (in_array('Games & Hobbies', $categories)) echo 'selected="selected"'; ?> value="Games &amp; Hobbies">Games &amp; Hobbies</option>
                        <optgroup label="">
                            <option <?php if (in_array('Games & Hobbies:Automotive', $categories)) echo 'selected="selected"'; ?> value="Games &amp; Hobbies:Automotive">Automotive</option>
                            <option <?php if (in_array('Games & Hobbies:Aviation', $categories)) echo 'selected="selected"'; ?> value="Games &amp; Hobbies:Aviation">Aviation</option>
                            <option <?php if (in_array('Games & Hobbies:Hobbies', $categories)) echo 'selected="selected"'; ?> value="Games &amp; Hobbies:Hobbies">Hobbies</option>
                            <option <?php if (in_array('Games & Hobbies:Other Games', $categories)) echo 'selected="selected"'; ?> value="Games &amp; Hobbies:Other Games">Other Games</option>
                            <option <?php if (in_array('Games & Hobbies:Video Games', $categories)) echo 'selected="selected"'; ?> value="Games &amp; Hobbies:Video Games">Video Games</option>
                        </optgroup>
                        <option <?php if (in_array('Government & Organizations', $categories)) echo 'selected="selected"'; ?> value="Government &amp; Organizations">Government &amp; Organizations</option>
                        <optgroup label="">
                            <option <?php if (in_array('Government & Organizations:Local', $categories)) echo 'selected="selected"'; ?> value="Government &amp; Organizations:Local">Local</option>
                            <option <?php if (in_array('Government & Organizations:National', $categories)) echo 'selected="selected"'; ?> value="Government &amp; Organizations:National">National</option>
                            <option <?php if (in_array('Government & Organizations:Non-Profit', $categories)) echo 'selected="selected"'; ?> value="Government &amp; Organizations:Non-Profit">Non-Profit</option>
                            <option <?php if (in_array('Government & Organizations:Regional', $categories)) echo 'selected="selected"'; ?> value="Government &amp; Organizations:Regional">Regional</option>
                        </optgroup>
                        <option <?php if (in_array('Health', $categories)) echo 'selected="selected"'; ?> value="Health">Health</option>
                        <optgroup label="">
                            <option <?php if (in_array('Health:Alternative Health', $categories)) echo 'selected="selected"'; ?> value="Health:Alternative Health">Alternative Health</option>
                            <option <?php if (in_array('Health:Fitness & Nutrition', $categories)) echo 'selected="selected"'; ?> value="Health:Fitness &amp; Nutrition">Fitness &amp; Nutrition</option>
                            <option <?php if (in_array('Health:Self-Help', $categories)) echo 'selected="selected"'; ?> value="Health:Self-Help">Self-Help</option>
                            <option <?php if (in_array('Health:Sexuality', $categories)) echo 'selected="selected"'; ?> value="Health:Sexuality">Sexuality</option>
                        </optgroup>
                        <option <?php if (in_array('Kids & Family', $categories)) echo 'selected="selected"'; ?> value="Kids &amp; Family">Kids &amp; Family</option>
                        <option <?php if (in_array('Music', $categories)) echo 'selected="selected"'; ?> value="Music">Music</option>
                        <option <?php if (in_array('News & Politics', $categories)) echo 'selected="selected"'; ?> value="News &amp; Politics">News &amp; Politics</option>
                        <option <?php if (in_array('Religion & Spirituality', $categories)) echo 'selected="selected"'; ?> value="Religion &amp; Spirituality">Religion &amp; Spirituality</option>
                        <optgroup label="">
                            <option <?php if (in_array('Religion & Spirituality:Buddhism', $categories)) echo 'selected="selected"'; ?> value="Religion &amp; Spirituality:Buddhism">Buddhism</option>
                            <option <?php if (in_array('Religion & Spirituality:Christianity', $categories)) echo 'selected="selected"'; ?> value="Religion &amp; Spirituality:Christianity">Christianity</option>
                            <option <?php if (in_array('Religion & Spirituality:Hinduism', $categories)) echo 'selected="selected"'; ?> value="Religion &amp; Spirituality:Hinduism">Hinduism</option>
                            <option <?php if (in_array('Religion & Spirituality:Islam', $categories)) echo 'selected="selected"'; ?> value="Religion &amp; Spirituality:Islam">Islam</option>
                            <option <?php if (in_array('Religion & Spirituality:Judaism', $categories)) echo 'selected="selected"'; ?> value="Religion &amp; Spirituality:Judaism">Judaism</option>
                            <option <?php if (in_array('Religion & Spirituality:Other', $categories)) echo 'selected="selected"'; ?> value="Religion &amp; Spirituality:Other">Other</option>
                            <option <?php if (in_array('Religion & Spirituality:Spirituality', $categories)) echo 'selected="selected"'; ?> value="Religion &amp; Spirituality:Spirituality">Spirituality</option>
                        </optgroup>
                        <option <?php if (in_array('Science & Medicine', $categories)) echo 'selected="selected"'; ?> value="Science &amp; Medicine">Science &amp; Medicine</option>
                        <optgroup label="">
                            <option <?php if (in_array('Science & Medicine:Medicine', $categories)) echo 'selected="selected"'; ?> value="Science &amp; Medicine:Medicine">Medicine</option>
                            <option <?php if (in_array('Science & Medicine:Natural Sciences', $categories)) echo 'selected="selected"'; ?> value="Science &amp; Medicine:Natural Sciences">Natural Sciences</option>
                            <option <?php if (in_array('Science & Medicine:Social Sciences', $categories)) echo 'selected="selected"'; ?> value="Science &amp; Medicine:Social Sciences">Social Sciences</option>
                        </optgroup>
                        <option <?php if (in_array('Society & Culture', $categories)) echo 'selected="selected"'; ?> value="Society &amp; Culture">Society &amp; Culture</option>
                        <optgroup label="">
                            <option <?php if (in_array('', $categories)) echo 'selected="selected"'; ?> value="Society &amp; Culture:History">History</option>
                            <option <?php if (in_array('', $categories)) echo 'selected="selected"'; ?> value="Society &amp; Culture:Personal Journals">Personal Journals</option>
                            <option <?php if (in_array('', $categories)) echo 'selected="selected"'; ?> value="Society &amp; Culture:Philosophy">Philosophy</option>
                            <option <?php if (in_array('', $categories)) echo 'selected="selected"'; ?> value="Society &amp; Culture:Places &amp; Travel">Places &amp; Travel</option>
                        </optgroup>
                        <option <?php if (in_array('', $categories)) echo 'selected="selected"'; ?> value="Sports &amp; Recreation">Sports &amp; Recreation</option>
                        <optgroup label="">
                            <option <?php if (in_array('Sports & Recreation:Amateur', $categories)) echo 'selected="selected"'; ?> value="Sports &amp; Recreation:Amateur">Amateur</option>
                            <option <?php if (in_array('Sports & Recreation:College &amp; High School', $categories)) echo 'selected="selected"'; ?> value="Sports &amp; Recreation:College &amp; High School">College &amp; High School</option>
                            <option <?php if (in_array('Sports & Recreation:Outdoor', $categories)) echo 'selected="selected"'; ?> value="Sports &amp; Recreation:Outdoor">Outdoor</option>
                            <option <?php if (in_array('Sports & Recreation:Professional', $categories)) echo 'selected="selected"'; ?> value="Sports &amp; Recreation:Professional">Professional</option>
                        </optgroup>
                        <option <?php if (in_array('Technology', $categories)) echo 'selected="selected"'; ?> value="Technology">Technology</option>
                        <optgroup label="">
                            <option <?php if (in_array('Technology:Gadgets', $categories)) echo 'selected="selected"'; ?> value="Technology:Gadgets">Gadgets</option>
                            <option <?php if (in_array('Technology:Podcasting', $categories)) echo 'selected="selected"'; ?> value="Technology:Podcasting">Podcasting</option>
                            <option <?php if (in_array('Technology:Software How-To', $categories)) echo 'selected="selected"'; ?> value="Technology:Software How-To">Software How-To</option>
                            <option <?php if (in_array('Technology:Tech News', $categories)) echo 'selected="selected"'; ?> value="Technology:Tech News">Tech News</option>
                        </optgroup>
                        <option <?php if (in_array('TV & Film', $categories)) echo 'selected="selected"'; ?> value="TV &amp; Film">TV &amp; Film</option>
                    </select>
                </div>
            </li>
            <!--<li><label for='itunes_image' class='element'>Image</label><div class='element'>
                <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[3][element]' type='hidden' value='image' />
                <input id='itunes_image' name='UNL_MediaHub_Feed_NamespacedElements_itunes[3][value]' type='text' value='<?php echo getFieldValue($context, 'itunes', 'image'); ?>' size='55' />
                </div></li>-->
                
                <li><label for='itunes_explicit' class='element'>Explicit<span class="helper">Set to 'yes' if this feed contains explicit content</span></label>
                <div class='element'>
                    <input name="UNL_MediaHub_Feed_NamespacedElements_itunes[4][element]" type="hidden" value="explicit"/>
                    <select id="itunes_explicit" name="UNL_MediaHub_Feed_NamespacedElements_itunes[4][value]">
                        <?php
                        if (getFieldValue($context, 'itunes', 'explicit') == "yes") {
                            echo '<option value="">No</option><option value="yes" selected="selected">Yes</option>';
                        } else {
                            echo '<option value="">No</option><option value="yes">Yes</option>';
                        }
                        ?>
                    </select>
                </div></li>
                
                <li><label for='itunes_keywords' class='element'>Keywords<span class="helper">This tag allows users to search on a maximum of 12 text keywords. Use commas to separate keywords.</span></label><div class='element'>
                <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[5][element]' type='hidden' value='keywords' />
                <input id='itunes_keywords' name='UNL_MediaHub_Feed_NamespacedElements_itunes[5][value]' type='text' value='<?php echo getFieldValue($context, 'itunes', 'keywords'); ?>' size='55' />
                </div></li><li><label for='itunes_new-feed-url' class='element'>New-feed-url <span class="helper">This tag allows you to change the URL where the podcast feed is located.</span></label><div class='element'>
                <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[6][element]' type='hidden' value='new-feed-url' />
                <input id='itunes_new-feed-url' name='UNL_MediaHub_Feed_NamespacedElements_itunes[6][value]' type='text' value='<?php echo getFieldValue($context, 'itunes', 'new-feed-url'); ?>' size='55' />
                </div></li><li><label for='itunes_owner' class='element'>Owner <span class="helper">Information that will be used to contact the owner of the podcast. Not publicly displayed.</span></label><div class='element'>

                <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[7][element]' type='hidden' value='owner' />
                <input id='itunes_owner' name='UNL_MediaHub_Feed_NamespacedElements_itunes[7][value]' type='text' value='<?php echo getFieldValue($context, 'itunes', 'owner'); ?>' size='55' />
                </div></li>
                <li>
	                <label for='itunes_subtitle' class='element'>Subtitle <span class="helper">The contents of this tag are shown in the Description column in iTunes. The subtitle displays best if it is only a few words long.</span></label>
	                <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[8][element]' type='hidden' value='subtitle' />
	                <input id='itunes_subtitle' name='UNL_MediaHub_Feed_NamespacedElements_itunes[8][value]' type='text' value='<?php echo getFieldValue($context, 'itunes', 'subtitle'); ?>' size='55' />
                </li>
                <li>
	                <label for='itunes_summary' class='element'>Summary <span class="helper">The contents of this tag are shown in a separate window that appears when the "circled i" in the Description column is clicked. It also appears on the iTunes page for your podcast.</span></label>
	                <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[9][element]' type='hidden' value='summary' />
                    <textarea id="itunes_summary" name="UNL_MediaHub_Feed_NamespacedElements_itunes[9][value]" rows="5" cols="50"><?php echo getFieldValue($context, 'itunes', 'summary'); ?></textarea>
				</li>
				<li><label for="itunes_submit" class="element">&nbsp;</label><div class="element"><input id="itunes_submit" name="submit" value="Save" type="submit" /></div></li>
        </ol>
    </fieldset>
    <fieldset id="boxee_header" style="display:none">

        <legend>Boxee Options</legend>

        <ol>
            <li><label for='boxee_expiry' class='element'>Expiry</label><div class='element'>
                <input name='UNL_MediaHub_Feed_NamespacedElements_boxee[0][element]' type='hidden' value='expiry' />
                <input id='boxee_expiry' name='UNL_MediaHub_Feed_NamespacedElements_boxee[0][value]' type='text' value='<?php echo getFieldValue($context, 'boxee', 'expiry'); ?>' size='55' />
                </div></li>
                <!--<li><label for='boxee_background-image' class='element'>Background-image</label><div class='element'>
                <input name='UNL_MediaHub_Feed_NamespacedElements_boxee[1][element]' type='hidden' value='background-image' />
                <input id='boxee_background-image' name='UNL_MediaHub_Feed_NamespacedElements_boxee[1][value]' type='text' value='<?php echo getFieldValue($context, 'boxee', 'background-image'); ?>' size='55' />
                </div></li>
                -->
                <li><label for='boxee_interval' class='element'>Interval</label><div class='element'>

                <input name='UNL_MediaHub_Feed_NamespacedElements_boxee[2][element]' type='hidden' value='interval' />
                <input id='boxee_interval' name='UNL_MediaHub_Feed_NamespacedElements_boxee[2][value]' type='text' value='<?php echo getFieldValue($context, 'boxee', 'interval'); ?>' size='55' />
                </div></li><li><label for='boxee_category' class='element'>Category</label><div class='element'>
                <input name='UNL_MediaHub_Feed_NamespacedElements_boxee[3][element]' type='hidden' value='category' />
                <input id='boxee_category' name='UNL_MediaHub_Feed_NamespacedElements_boxee[3][value]' type='text' value='<?php echo getFieldValue($context, 'boxee', 'category'); ?>' size='55' />
                </div></li>            <li><label for="boxee_submit" class="element">&nbsp;</label><div class="element"><input id="boxee_submit" name="submit" value="Save" type="submit" /></div></li>
        </ol>
    </fieldset>
        <fieldset id="media_header">

        <legend>Media RSS Options</legend>
        <ol>
            <li><label for='media_rating' class='element'>Rating <span class="helper">Appropriate audience: adult or nonadult. Leave blank if no restrictions.</span></label><div class='element'>

                <input name='UNL_MediaHub_Feed_NamespacedElements_media[0][element]' type='hidden' value='rating' />
                <input id='media_rating' name='UNL_MediaHub_Feed_NamespacedElements_media[0][value]' type='text' value='<?php echo getFieldValue($context, 'media', 'rating'); ?>' size='55' />
                </div></li><li><label for='media_title' class='element'>Title</label><div class='element'>
                <input name='UNL_MediaHub_Feed_NamespacedElements_media[1][element]' type='hidden' value='title' />
                <input id='media_title' name='UNL_MediaHub_Feed_NamespacedElements_media[1][value]' type='text' value='<?php echo getFieldValue($context, 'media', 'title'); ?>' size='55' />
                </div></li>
                <li>
                	<label for='media_description' class='element'>Description</label>
                	<input name='UNL_MediaHub_Feed_NamespacedElements_media[2][element]' type='hidden' value='description' />
				    <textarea id="media_description" name="UNL_MediaHub_Feed_NamespacedElements_media[2][value]" rows="5" cols="50"><?php echo getFieldValue($context, 'media', 'description'); ?></textarea>
				</li>
				<li><label for='media_keywords' class='element'>Keywords<span class="helper">Comma seperated list of highly relevant keywords/tags describing the channel.</span></label><div class='element'>
                <input name='UNL_MediaHub_Feed_NamespacedElements_media[3][element]' type='hidden' value='keywords' />
                <input id='media_keywords' name='UNL_MediaHub_Feed_NamespacedElements_media[3][value]' type='text' value='<?php echo getFieldValue($context, 'media', 'keywords'); ?>' size='55' />
                </div></li><li><label for='media_thumbnail' class='element'>Thumbnail <span class="helper">Allows particular images to be used as representative images for the media object.</span></label><div class='element'>
                <input name='UNL_MediaHub_Feed_NamespacedElements_media[4][element]' type='hidden' value='thumbnail' />
                <input id='media_thumbnail' name='UNL_MediaHub_Feed_NamespacedElements_media[4][value]' type='text' value='<?php echo getFieldValue($context, 'media', 'thumbnail'); ?>' size='55' />
                </div></li><li><label for='media_category' class='element'>Category <span class="helper">Allows a taxonomy to be set that gives an indication of the type of media content, and its particular contents</span></label><div class='element'>

                <input name='UNL_MediaHub_Feed_NamespacedElements_media[5][element]' type='hidden' value='category' />
                <input id='media_category' name='UNL_MediaHub_Feed_NamespacedElements_media[5][value]' type='text' value='<?php echo getFieldValue($context, 'media', 'category'); ?>' size='55' />
                </div></li><li><label for='media_player' class='element'>Player <span class="helper">Allows the media object to be accessed through a web browser media player console.</span></label><div class='element'>
                <input name='UNL_MediaHub_Feed_NamespacedElements_media[6][element]' type='hidden' value='player' />
                <input id='media_player' name='UNL_MediaHub_Feed_NamespacedElements_media[6][value]' type='text' value='<?php echo getFieldValue($context, 'media', 'player'); ?>' size='55' />
                </div></li><li><label for='media_credit' class='element'>Credit <span class="helper">Notable entity and the contribution to the creation of the media object.</span></label><div class='element'>
                <input name='UNL_MediaHub_Feed_NamespacedElements_media[7][element]' type='hidden' value='credit' />
                <input id='media_credit' name='UNL_MediaHub_Feed_NamespacedElements_media[7][value]' type='text' value='<?php echo getFieldValue($context, 'media', 'credit'); ?>' size='55' />

                </div></li><li><label for='media_copyright' class='element'>Copyright <span class="helper">Copyright information for media object.</span></label><div class='element'>
                <input name='UNL_MediaHub_Feed_NamespacedElements_media[8][element]' type='hidden' value='copyright' />
                <input id='media_copyright' name='UNL_MediaHub_Feed_NamespacedElements_media[8][value]' type='text' value='<?php echo getFieldValue($context, 'media', 'copyright'); ?>' size='55' />
                </div></li><li style="display:none;"><label for='media_text' class='element'>Text <span class="helper">Allows the inclusion of a text transcript, closed captioning, or lyrics of the media content.</span></label><div class='element'>
                <input name='UNL_MediaHub_Feed_NamespacedElements_media[9][element]' type='hidden' value='text' />
                <input id='media_text' name='UNL_MediaHub_Feed_NamespacedElements_media[9][value]' type='text' value='<?php echo getFieldValue($context, 'media', 'text'); ?>' size='55' />
                </div></li><li><label for='media_restriction' class='element'>Restriction <span class="helper">Allows restrictions to be placed on the aggregator rendering the media in the feed.</span></label><div class='element'>

                <input name='UNL_MediaHub_Feed_NamespacedElements_media[10][element]' type='hidden' value='restriction' />
                <input id='media_restriction' name='UNL_MediaHub_Feed_NamespacedElements_media[10][value]' type='text' value='<?php echo getFieldValue($context, 'media', 'restriction'); ?>' size='55' />
                </div></li>            <li><label for="media_submit" class="element">&nbsp;</label><div class="element"><input id="media_submit" name="submit" value="Save" type="submit" /></div></li>
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
            $ns_class = 'UNL_MediaHub_Feed_NamespacedElements_'.$class;
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
</div>
<div class="one_col right" id="outpostExtension">
<h3>Delivery Options <span>Coming soon!</span></h3>
<p>Channels in MediaHub can be delivered through <strong>official UNL outposts</strong>. Make sure you take advantage of these:</p>
<ul>
    <li class="itunes"><a href="http://www.apple.com/education/itunes-u/">iTunesU</a></li>
    <li class="boxee"><a href="http://www.boxee.tv/">Boxee</a></li>
    <li class="youtube"><a href="http://www.youtube.com/unl">YouTube</a></li>
</ul>
</div>