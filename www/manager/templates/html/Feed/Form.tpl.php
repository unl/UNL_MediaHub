<?php $feed_url = htmlentities(UNL_MediaHub_Controller::getURL($context), ENT_QUOTES); ?>
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

<div class="wdn-band mh-channel-edit">
    <div class="wdn-inner-wrapper">
        <form action="<?php echo $context->action; ?>" method="post" name="feed" id="feed" enctype="multipart/form-data" class="">
            <h1 class="wdn-brand clear-top wdn-pull-left">
                <?php echo (isset($context->feed))?'Edit':'Create'; ?> a Channel
                <?php if(isset($context->feed->id)): ?>
                    <span class="wdn-subhead">
                        <a class="users" href="<?php echo UNL_MediaHub_Manager::getURL(); ?>?view=permissions&amp;feed_id=<?php echo $context->feed->id; ?>">
                            <span class="wdn-icon wdn-icon-user">
                            </span> 
                            Edit Channel Users
                        </a>
                    </span>
                <?php endif; ?>    
            </h1>
            <input id="media_submit" name="submit" value="Save" class="wdn-button wdn-button-brand wdn-pull-right" type="submit" />

            <div class="clear">
            </div>
            <div class="wdn-grid-set">
                <div class="bp2-wdn-col-three-fifths">
                    <legend>Basic Information</legend> 
                    <fieldset>
                        <ol>
                            <li>
                                <label for="title">Title<span class="required">*</span></label>
                                <div class="mh-tooltip italic wdn-icon-info hang-right" id="title-details">
                                    <div>
                                        <p>
                                            Channel name displayed everywhere.
                                        </p>
                                    </div>
                                </div>
                                <input id="title" name="title" type="text" aria-describedby="title-details" value="<?php echo (isset($context->feed))? htmlentities($context->feed->title, ENT_QUOTES):''; ?>" size="55" />
                            </li>
                            <li>
                                <label for="description">Description<span class="required">*</span></label>
                                <div class="mh-tooltip italic wdn-icon-info" id="description-details">
                                    <div>
                                        <p>
                                            Explain what this channel is all about. Use a few sentences, but keep it to 1 paragraph.
                                        </p>
                                    </div>
                                </div>
                                <textarea id="description" name="description" rows="5" cols="50" aria-describedby="description-details"><?php echo (isset($context->feed))?htmlentities($context->feed->description):''; ?></textarea>
                            </li>
                        </ol>
                    </fieldset>
                </div>
                <div class="bp2-wdn-col-two-fifths wdn-pull-right">
                    <fieldset id="feed_header">
                        <legend>Channel Image</legend>
                        <ol>
                            <li>
                                <label class="element">Image File</label>
                                <div class="mh-tooltip italic wdn-icon-info hang-right" id="image-file-details">
                                    <div>
                                        <p>Images should follow the standard UNL image standards. <a href="https://github.com/unl/UNL_MediaHub/raw/master/designFiles/feedTemplate.psd">(get the sample template)</a></p>
                                    </div>
                                </div>
                                <input id="image_file" name="image_file" type="file" aria-describedby="image-file-details" />
                            </li>
                            <li>
                                <label for="image_title" class="element">Image Title</label>
                                 <span class="mh-tooltip italic wdn-icon-info" id="image-title-details"><div><p>Give the image a title, used in RSS feeds.</p></div></span>
                                <input id="image_title" name="image_title" type="text" aria-describedby="image-title-details" value="<?php echo (isset($context->feed))? htmlentities($context->feed->image_title, ENT_QUOTES):''; ?>" size="55" />
                            </li>
                            <li>
                                <label for="image_description" class="element">Image Description</label>
                                <div class="wdn-icon-info mh-tooltip italic" id="image-description-details"><div><p>Describe your image, used in RSS feeds.</p></div></div>
                                <textarea id="image_description" name="image_description" rows="5" cols="50" aria-describedby="image-description-details"><?php echo (isset($context->feed))?htmlentities($context->feed->image_description):''; ?></textarea>
                            </li>
                        </ol>
                    </fieldset>
                </div>
                <div class="bp2-wdn-col-three-fifths">
                    <div style="display: none;">
                        <input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="feed" />
                        <?php
                        if (isset($context->feed->id)) {
                            echo '<input type="hidden" id="id" name="id" value="'.$context->feed->id.'" />';
                        }
                        ?>
                    </div>

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
                    <fieldset id="itunes_header" >

                        <legend>iTunes U Options</legend>

                        <ol>
                            <li>
                                <label for='itunes_author' class='element'>Author</label>
                                <div class="wdn-icon-info mh-tooltip italic hang-right" id="itunes-author-details"><div><p>Used in the Artist column of iTunes</p></div></div>
                                <div class='element'>
                                    <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[0][element]' type='hidden' value='author' />
                                    <input id='itunes_author' name='UNL_MediaHub_Feed_NamespacedElements_itunes[0][value]' aria-describedby="itunes-author-details" type='text' value='<?php echo getFieldValue($context, 'itunes', 'author'); ?>' size='55' />
                                </div>
                            </li>
                            <li>
                                <label for='itunes_block' class='element'>Block <span class="helper">Keep this channel from appearing in iTunes</span>
                                </label>
                                <div class='element'>
                                    <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[1][element]' type='hidden' value='block' />
                                    <input id='itunes_block' name='UNL_MediaHub_Feed_NamespacedElements_itunes[1][value]' type='text' value='<?php echo getFieldValue($context, 'itunes', 'block'); ?>' size='55' />
                                </div>
                            </li>
                            <li>
                                <label for='itunes_category' class='element'>Category <span class="helper">Select an appropriate category listing.</span>
                                </label>
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


                            <li>
                                <label for='itunes_explicit' class='element'>Explicit</label>
                                <div class="mh-tooltip hang-right italic wdn-icon-info" id="itune-explicit-details">
                                    <div>
                                        <p>Set to 'yes' if this feed contains explicit content</p>
                                    </div>
                                </div>
                                
                                <div class='element'>
                                    <input name="UNL_MediaHub_Feed_NamespacedElements_itunes[4][element]" type="hidden" value="explicit"/>
                                    <select id="itunes_explicit" name="UNL_MediaHub_Feed_NamespacedElements_itunes[4][value]" aria-describedby="itune-explicit-details">
                                        <?php
                                        if (getFieldValue($context, 'itunes', 'explicit') == "yes") {
                                            echo '<option value="">No</option>
                                            <option value="yes" selected="selected">Yes</option>';
                                        } else {
                                            echo '<option value="">No</option>
                                            <option value="yes">Yes</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </li>

                            <li>
                                <label for='itunes_keywords' class='element'>Keywords</label>
                                <div class="wdn-icon-info mh-tooltip hang-right italic" id="itune-keyword-details">
                                    <div>
                                        <p>This tag allows users to search on a maximum of 12 text keywords. Use commas to separate keywords.</p>
                                    </div>
                                </div>
                                
                                <div class='element'>
                                    <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[5][element]' type='hidden' value='keywords' />
                                    <input id='itunes_keywords' name='UNL_MediaHub_Feed_NamespacedElements_itunes[5][value]' aria-describedby="itune-keyword-details" type='text' value='<?php echo getFieldValue($context, 'itunes', 'keywords'); ?>' size='55' />
                                </div>
                            </li>
                            <li>
                                <label for='itunes_new-feed-url' class='element'>New-feed-url</label>
                                <div class="mh-tooltip hang-right wdn-icon-info italic" id="itune-new-feed-url-details">
                                    <div>
                                        <p>This tag allows you to change the URL where the podcast feed is located.</p>
                                    </div>
                                </div>
                                
                                <div class='element'>
                                    <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[6][element]' type='hidden' value='new-feed-url' />
                                    <input id='itunes_new-feed-url' name='UNL_MediaHub_Feed_NamespacedElements_itunes[6][value]' aria-describedby="itune-new-feed-url-details" type='text' value='<?php echo getFieldValue($context, 'itunes', 'new-feed-url'); ?>' size='55' />
                                </div>
                            </li>
                            <li>
                                <label for='itunes_owner' class='element'>Owner</label>
                                <div class="wdn-icon-info mh-tooltip hang-right italic" id="itunes-owner-details">
                                    <div>
                                        <p>Information that will be used to contact the owner of the podcast. Not publicly displayed.</p>
                                    </div>
                                </div>
                                
                                <div class='element'>

                                    <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[7][element]' type='hidden' value='owner' />
                                    <input id='itunes_owner' name='UNL_MediaHub_Feed_NamespacedElements_itunes[7][value]' aria-describedby="itunes-owner-details" type='text' value='<?php echo getFieldValue($context, 'itunes', 'owner'); ?>' size='55' />
                                </div>
                            </li>
                            <li>
                                <label for='itunes_subtitle' class='element'>Subtitle</label>
                                <div class="italic wdn-icon-info mh-tooltip hang-right" id="itunes-subtitle-details"><div><p>The contents of this tag are shown in the Description column in iTunes. The subtitle displays best if it is only a few words long.</p></div></div>
                                <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[8][element]' type='hidden' value='subtitle' />
                                <input id='itunes_subtitle' name='UNL_MediaHub_Feed_NamespacedElements_itunes[8][value]' aria-describedby="itunes-subtitle-details" type='text' value='<?php echo getFieldValue($context, 'itunes', 'subtitle'); ?>' size='55' />
                            </li>
                            <li>
                                <label for='itunes_summary' class='element'>Summary</label>
                                <div class="italic mh-tooltip hang-right wdn-icon-info" id="itunes-summary-details"><div><p>The contents of this tag are shown in a separate window that appears when the "circled i" in the Description column is clicked. It also appears on the iTunes page for your podcast.</p></div></div>
                                <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[9][element]' type='hidden' value='summary' />
                                <textarea id="itunes_summary" name="UNL_MediaHub_Feed_NamespacedElements_itunes[9][value]" aria-describedby="itunes-summary-details" rows="5" cols="50"><?php echo getFieldValue($context, 'itunes', 'summary'); ?></textarea>
                            </li>
                        </ol>
                    </fieldset>
                    <fieldset id="media_header">
                        <legend>Media RSS Options</legend>
                        <ol>
                            <li>
                                <label for='media_rating' class='element'>Rating</label>
                                <div class="wdn-icon-info mh-tooltip hang-right italic" id="rating-details"><div><p>Appropriate audience: adult or nonadult. Leave blank if no restrictions.</p></div></div>
                                <div class='element'>
                                    <input name='UNL_MediaHub_Feed_NamespacedElements_media[0][element]' type='hidden' value='rating' />
                                    <input id='media_rating' name='UNL_MediaHub_Feed_NamespacedElements_media[0][value]' aria-describedby="rating-details" type='text' value='<?php echo getFieldValue($context, 'media', 'rating'); ?>' size='55' />
                                </div>
                            </li>

                            <li>
                                <label for='media_title' class='element'>Title</label>
                                <div class='element'>
                                    <input name='UNL_MediaHub_Feed_NamespacedElements_media[1][element]' type='hidden' value='title' />
                                    <input id='media_title' name='UNL_MediaHub_Feed_NamespacedElements_media[1][value]' type='text' value='<?php echo getFieldValue($context, 'media', 'title'); ?>' size='55' />
                                </div>
                            </li>
                            <li>
                                <label for='media_description' class='element'>Description</label>
                                <input name='UNL_MediaHub_Feed_NamespacedElements_media[2][element]' type='hidden' value='description' />
                                <textarea id="media_description" name="UNL_MediaHub_Feed_NamespacedElements_media[2][value]" rows="5" cols="50"><?php echo getFieldValue($context, 'media', 'description'); ?></textarea>
                            </li>
                            <li>
                                <label for='media_keywords' class='element'>Keywords</label>
                                <div class="italic wdn-icon-info mh-tooltip hang-right" id="keyword-details"><div><p>Comma seperated list of highly relevant keywords/tags describing the channel.</p></div></div>
                                <div class='element'>
                                    <input name='UNL_MediaHub_Feed_NamespacedElements_media[3][element]' type='hidden' value='keywords' />
                                    <input id='media_keywords' name='UNL_MediaHub_Feed_NamespacedElements_media[3][value]' type='text' aria-describedby="keyword-details" value='<?php echo getFieldValue($context, 'media', 'keywords'); ?>' size='55' />
                                </div>
                            </li>
                            <li>
                                <label for='media_thumbnail' class='element'>Thumbnail</label>
                                <div class="italic wdn-icon-info mh-tooltip hang-right" id="thumbnail-details"><div><p>Allows particular images to be used as representative images for the media object.</p></div></div>
                                <div class='element'>
                                    <input name='UNL_MediaHub_Feed_NamespacedElements_media[4][element]' type='hidden' value='thumbnail' />
                                    <input id='media_thumbnail' name='UNL_MediaHub_Feed_NamespacedElements_media[4][value]' aria-describedby="thumbnail-details" type='text' value='<?php echo getFieldValue($context, 'media', 'thumbnail'); ?>' size='55' />
                                </div>
                            </li>
                            <li>
                                <label for='media_category' class='element'>Category</label>
                                <div class="wdn-icon-info mh-tooltip hang-right wdn-icon-info" id="category-details"><div><p>Allows a taxonomy to be set that gives an indication of the type of media content, and its particular contents</p></div></div>
                                <div class='element'>
                                    <input name='UNL_MediaHub_Feed_NamespacedElements_media[5][element]' type='hidden' value='category' />
                                    <input id='media_category' name='UNL_MediaHub_Feed_NamespacedElements_media[5][value]' aria-describedby="category-details" type='text' value='<?php echo getFieldValue($context, 'media', 'category'); ?>' size='55' />
                                </div>
                            </li>
                            <li>
                                <label for='media_player' class='element'>Player</label>
                                <div class="wdn-icon-info mh-tooltip hang-right italic" id="player-details"><div><p>Allows the media object to be accessed through a web browser media player console.</p></div></div>
                                <div class='element'>
                                    <input name='UNL_MediaHub_Feed_NamespacedElements_media[6][element]' type='hidden' value='player' />
                                    <input id='media_player' name='UNL_MediaHub_Feed_NamespacedElements_media[6][value]' aria-describedby="player-details" type='text' value='<?php echo getFieldValue($context, 'media', 'player'); ?>' size='55' />
                                </div>
                            </li>
                            <li>
                                <label for='media_credit' class='element'>Credit</label>
                                <div class="wdn-icon-info mh-tooltip hang-right italic" id="credit-details"><div><p>Notable entity and the contribution to the creation of the media object.</p></div></div>
                                <div class='element'>
                                    <input name='UNL_MediaHub_Feed_NamespacedElements_media[7][element]' type='hidden' value='credit' />
                                    <input id='media_credit' name='UNL_MediaHub_Feed_NamespacedElements_media[7][value]' aria-describedby="credit-details" type='text' value='<?php echo getFieldValue($context, 'media', 'credit'); ?>' size='55' />
                                </div>
                            </li>
                            <li>
                                <label for='media_copyright' class='element'>Copyright</label>
                                <div class="mh-tooltip hang-right italic wdn-icon-info" id="copyright-details"><div><p>Copyright information for media object.</p></div></div>
                                <div class='element'>
                                    <input name='UNL_MediaHub_Feed_NamespacedElements_media[8][element]' type='hidden' value='copyright' />
                                    <input id='media_copyright' name='UNL_MediaHub_Feed_NamespacedElements_media[8][value]' aria-describedby="copyright-details" type='text' value='<?php echo getFieldValue($context, 'media', 'copyright'); ?>' size='55' />
                                </div>
                            </li>
                            <li style="display:none;">
                                <label for='media_text' class='element'>Text</label>
                                <div class="mh-tooltip hang-right wdn-icon-info italic" id="text-details"><div><p>Allows the inclusion of a text transcript, closed captioning, or lyrics of the media content.</p></div></div>
                                <div class='element'>
                                    <input name='UNL_MediaHub_Feed_NamespacedElements_media[9][element]' type='hidden' value='text' />
                                    <input id='media_text' name='UNL_MediaHub_Feed_NamespacedElements_media[9][value]' aria-describedby="text-details" type='text' value='<?php echo getFieldValue($context, 'media', 'text'); ?>' size='55' />
                                </div>
                            </li>
                            <li>
                                <label for='media_restriction' class='element'>Restriction</label>
                                <div class="wdn-icon-info mh-tooltip hang-right italic" id="restriction-details"><div><p>Allows restrictions to be placed on the aggregator rendering the media in the feed.</p></div></div>
                                <div class='element'>
                                    <input name='UNL_MediaHub_Feed_NamespacedElements_media[10][element]' type='hidden' value='restriction' />
                                    <input id='media_restriction' name='UNL_MediaHub_Feed_NamespacedElements_media[10][value]' aria-describedby="restriction-details" type='text' value='<?php echo getFieldValue($context, 'media', 'restriction'); ?>' size='55' />
                                </div>
                            </li>
                            <li>
                                <label for="media_submit" class="element">&nbsp;</label>
                                <div class="element">
                                    <input id="media_submit" class="wdn-pull-left"name="submit" value="Save" class="wdn-button wdn-button-brand" type="submit" />
                                    <?php if (isset($context->feed)): ?>
                                        <?php echo $savvy->render($context->feed, 'Feed/DeleteForm.tpl.php'); ?>
                                    <?php endif; ?>
                                </div>
                            </li>
                        </ol>
                    </fieldset>
                </div>
            </div>
        </form>
    </div>
</div>

