<?php
$feed_url = htmlentities(UNL_MediaHub_Controller::getURL($context), ENT_QUOTES);
$url = htmlentities(UNL_MediaHub_Controller::getURL($context->feed), ENT_QUOTES);
$user = UNL_MediaHub_AuthService::getInstance()->getUser();
if (isset($context->feed) && !$context->feed->userCanEdit($user)) {
    throw new UNL_MediaHub_RuntimeException('You do not have permission to manage this channel.', 403);
}
$page->addScriptDeclaration("
    require(['jquery'], function($) {
        $(document).ready(function() {
            $('#extensions input').change(function() {
                if(this.checked){
                    $('#'+this.value+'_header').slideDown(400);
                } else {
                    $('#'+this.value+'_header').slideUp(400);
                }
                return true;
            });
            $('#description').change(function(){
                if ($('#itunes_summary').val() == '') {
                    $('#itunes_summary').val($(this).val());
                }
                if ($('#media_description').val() == '') {
                    $('#media_description').val($(this).val());
                }
            });
            $('#title').change(function(){
                if ($('#media_title').val() == '') {
                    $('#media_title').val($(this).val());
                }
            });
        });
    });
");
?>
<h1 class="dcf-mt-4 dcf-txt-h2">
    <?php echo (isset($context->feed))?'Edit':'Create'; ?> Channel<?php echo (isset($context->feed))? ': ' . UNL_MediaHub::escape($context->feed->title):''; ?>
</h1>
<ul class="dcf-p-1 dcf-list-bare dcf-list-inline dcf-txt-xs dcf-bg-overlay-dark">
    <li class="dcf-m-0">
        <a class="dcf-btn dcf-btn-inverse-tertiary" href="<?php echo UNL_MediaHub_Controller::getURL(); ?>channels/<?php echo $context->feed->id; ?>">View Channel</a>
    </li>
    <li class="dcf-m-0">
        <a class="dcf-btn dcf-btn-inverse-tertiary" href="<?php echo UNL_MediaHub_Manager::getURL(); ?>?view=feedstats&amp;feed_id=<?php echo $context->feed->id; ?>">View Channel Stats</a>
    </li>
    <li class="dcf-m-0">
        <a class="dcf-btn dcf-btn-inverse-tertiary" href="<?php echo UNL_MediaHub_Manager::getURL(); ?>?view=permissions&amp;feed_id=<?php echo $context->feed->id; ?>">Edit Channel Users</a>
    </li>
</ul>
<div class="dcf-mt-6 dcf-mb-6 ">
    <form class="dcf-form mh-channel-edit" action="<?php echo $context->action; ?>" method="post" name="feed" id="feed" enctype="multipart/form-data">
        <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
        <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">

        <?php
        $errorNotice = new StdClass();
        $errorNotice->title = 'Channel Errors';
        echo $savvy->render($errorNotice, 'ErrorListNotice.tpl.php');
        ?>

        <fieldset>
            <legend class="dcf-legend">Basic Information</legend>
            <div class="dcf-form-group">
                <label for="title">Title <small class="dcf-required">Required</small></label>
                <div class="dcf-popup dcf-d-inline" id="title-details" data-hover="true" data-point="true">
                    <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                            viewBox="0 0 24 24"
                        >
                            <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23 C5.71,23,1,18.29,1,12.5
                                C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                            <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                                C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5 C8.224,19,8,19.224,8,19.5
                                S8.224,20,8.5,20h6c0.276,0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                            <circle cx="11" cy="6.5" r="1"></circle>
                            <g>
                                <path fill="none" d="M0 0H24V24H0z"></path>
                            </g>
                        </svg>
                    </button>
                    <div
                        class="
                            dcf-popup-content
                            unl-cream
                            unl-bg-blue
                            dcf-p-1
                            dcf-rounded
                        "
                        style="min-width: 25ch;"
                    >
                        <p class="dcf-m-0 dcf-regular">
                            Channel name displayed everywhere.
                        </p>
                    </div>
                </div>
                <input id="title" name="title" type="text" aria-describedby="title-details" value="<?php echo (isset($context->feed))? UNL_MediaHub::escape($context->feed->title):''; ?>" size="55" />
            </div>
            <div class="dcf-form-group">
                <label for="description">Description <small class="dcf-required">Required</small></label>
                <div class="dcf-popup dcf-d-inline" id="description-details" data-hover="true" data-point="true">
                    <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                            viewBox="0 0 24 24"
                        >
                            <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23 C5.71,23,1,18.29,1,12.5
                                C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                            <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                                C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5 C8.224,19,8,19.224,8,19.5
                                S8.224,20,8.5,20h6c0.276,0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                            <circle cx="11" cy="6.5" r="1"></circle>
                            <g>
                                <path fill="none" d="M0 0H24V24H0z"></path>
                            </g>
                        </svg>
                    </button>
                    <div
                        class="
                            dcf-popup-content
                            unl-cream
                            unl-bg-blue
                            dcf-p-1
                            dcf-rounded
                        "
                        style="min-width: 25ch;"
                    >
                        <p class="dcf-m-0 dcf-regular">
                            Explain what this channel is all about. Use a few sentences, but keep it to 1 paragraph.
                        </p>
                    </div>
                </div>
                <textarea id="description" name="description" aria-describedby="description-details"><?php echo (isset($context->feed))?htmlentities($context->feed->description):''; ?></textarea>
            </div>
        </fieldset>
        <fieldset id="feed_header">
            <legend class="dcf-legend">Channel Image</legend>
            <?php if(isset($context->feed->id) && $context->feed->hasImage()): ?>
                <div class="dcf-form-group">
                    <div class="mh-channel-thumb">
                        <img src="<?php echo $url; ?>/image" alt="<?php echo htmlentities($context->feed->title, ENT_QUOTES); ?> Image">
                    </div>
                </div>
            <?php endif; ?>
            <div class="dcf-form-group">
                <label for="image_file">Image File</label>
                <div class="dcf-popup dcf-d-inline" id="image-file-details" data-hover="true" data-point="true">
                    <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                            viewBox="0 0 24 24"
                        >
                            <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23 C5.71,23,1,18.29,1,12.5
                                C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                            <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                                C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5 C8.224,19,8,19.224,8,19.5
                                S8.224,20,8.5,20h6c0.276,0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                            <circle cx="11" cy="6.5" r="1"></circle>
                            <g>
                                <path fill="none" d="M0 0H24V24H0z"></path>
                            </g>
                        </svg>
                    </button>
                    <div
                        class="
                            dcf-popup-content
                            unl-cream
                            unl-bg-blue
                            dcf-p-1
                            dcf-rounded
                        "
                        style="min-width: 25ch;"
                    >
                        <p class="dcf-m-0 dcf-regular">
                            Images should follow the standard UNL image standards.
                            <a href="https://github.com/unl/UNL_MediaHub/raw/master/designFiles/feedTemplate.psd">(get
                                the sample template)</a>
                        </p>
                    </div>
                </div>
                <input id="image_file" name="image_file" type="file" aria-describedby="image-file-details" />
            </div>
            <div class="dcf-form-group">
                <label for="image_title">Image Title</label>
                <div class="dcf-popup dcf-d-inline" id="image-title-details" data-hover="true" data-point="true">
                    <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                            viewBox="0 0 24 24"
                        >
                            <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23 C5.71,23,1,18.29,1,12.5
                                C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                            <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                                C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5 C8.224,19,8,19.224,8,19.5
                                S8.224,20,8.5,20h6c0.276,0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                            <circle cx="11" cy="6.5" r="1"></circle>
                            <g>
                                <path fill="none" d="M0 0H24V24H0z"></path>
                            </g>
                        </svg>
                    </button>
                    <div
                        class="
                            dcf-popup-content
                            unl-cream
                            unl-bg-blue
                            dcf-p-1
                            dcf-rounded
                        "
                        style="min-width: 25ch;"
                    >
                        <p class="dcf-m-0 dcf-regular">
                            Give the image a title, used in RSS feeds.
                        </p>
                    </div>
                </div>
                <input id="image_title" name="image_title" type="text" aria-describedby="image-title-details" value="<?php echo (isset($context->feed))? htmlentities($context->feed->image_title, ENT_QUOTES):''; ?>" />
            </div>
            <div class="dcf-form-group">
                <label for="image_description">Image Description</label>
                <div class="dcf-popup dcf-d-inline" id="image-description-details" data-hover="true" data-point="true">
                    <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                            viewBox="0 0 24 24"
                        >
                            <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23 C5.71,23,1,18.29,1,12.5
                                C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                            <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                                C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5 C8.224,19,8,19.224,8,19.5
                                S8.224,20,8.5,20h6c0.276,0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                            <circle cx="11" cy="6.5" r="1"></circle>
                            <g>
                                <path fill="none" d="M0 0H24V24H0z"></path>
                            </g>
                        </svg>
                    </button>
                    <div
                        class="
                            dcf-popup-content
                            unl-cream
                            unl-bg-blue
                            dcf-p-1
                            dcf-rounded
                        "
                        style="min-width: 25ch;"
                    >
                        <p class="dcf-m-0 dcf-regular">
                            Describe your image, used in RSS feeds.
                        </p>
                    </div>
                </div>
                <textarea id="image_description" name="image_description" aria-describedby="image-description-details"><?php echo (isset($context->feed))?htmlentities($context->feed->image_description):''; ?></textarea>
            </div>
        </fieldset>

        <div style="display: none;">
            <input type="hidden" name="__unlmy_posttarget" value="feed" />
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
                    return UNL_MediaHub::escape($element->value);
                }
            }
            return '';
        }
        function getFieldAttributes($context, $xmlns, $element)
        {
            if (isset($context->feed)) {
                $class = 'UNL_MediaHub_Feed_NamespacedElements_'.$xmlns;
                if ($element = call_user_func($class .'::feedHasElement', $context->feed->id, $element, $xmlns)) {
                    return UNL_MediaHub::escape(serialize($element->attributes));
                }
            }
            return '';
        }
        ?>
        <fieldset id="itunes_header" >

            <legend class="dcf-legend">iTunes U Options</legend>
            <div class="dcf-form-group">
                <label for='itunes_author'>Author</label>
                <div class="dcf-popup dcf-d-inline" id="itunes-author-details" data-hover="true" data-point="true">
                    <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                            viewBox="0 0 24 24"
                        >
                            <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23 C5.71,23,1,18.29,1,12.5
                                C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                            <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                                C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5 C8.224,19,8,19.224,8,19.5
                                S8.224,20,8.5,20h6c0.276,0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                            <circle cx="11" cy="6.5" r="1"></circle>
                            <g>
                                <path fill="none" d="M0 0H24V24H0z"></path>
                            </g>
                        </svg>
                    </button>
                    <div
                        class="
                            dcf-popup-content
                            unl-cream
                            unl-bg-blue
                            dcf-p-1
                            dcf-rounded
                        "
                        style="min-width: 25ch;"
                    >
                        <p class="dcf-m-0 dcf-regular">
                            Used in the Artist column of iTunes
                        </p>
                    </div>
                </div>
                <div>
                    <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[0][element]' type='hidden' value='author' />
                    <input id='itunes_author' name='UNL_MediaHub_Feed_NamespacedElements_itunes[0][value]' aria-describedby="itunes-author-details" type='text' value='<?php echo getFieldValue($context, 'itunes', 'author'); ?>' size='55' />
                </div>
            </div>
            <div class="dcf-form-group">
                <label for='itunes_block'>Block <span class="dcf-form-help">Keep this channel from appearing in iTunes</span>
                </label>
                <div>
                    <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[1][element]' type='hidden' value='block' />
                    <input id='itunes_block' name='UNL_MediaHub_Feed_NamespacedElements_itunes[1][value]' type='text' value='<?php echo getFieldValue($context, 'itunes', 'block'); ?>' size='55' />
                </div>
            </div>
            <div class="dcf-form-group">
                <label for='itunes_category'>Category <span class="dcf-form-help">Select an appropriate category listing.</span>
                </label>
                <div>
                    <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[2][element]' type='hidden' value='category' />
                    <?php
                    $categories = array();
                    if(isset($context->feed->id)){
                        if ($value = UNL_MediaHub_Feed_NamespacedElements_itunes::feedHasElement($context->feed->id, 'category', 'itunes')) {
                            $categories = $value['attributes']['text'];
                        };
                    };
                    ?>
                    <select id='itunes_category' name='UNL_MediaHub_Feed_NamespacedElements_itunes[2][attributes][]' multiple="multiple">
                        <option <?php if (in_array('Arts', $categories)) echo 'selected="selected"'; ?> value="Arts">Arts</option>
                        <optgroup label="">
                            <option <?php if (in_array('Arts:Books', $categories)) echo 'selected="selected"'; ?> value="Arts:Books">Books</option>
                            <option <?php if (in_array('Arts:Design', $categories)) echo 'selected="selected"'; ?> value="Arts:Design">Design</option>
                            <option <?php if (in_array('Arts:Fashion & Beauty', $categories)) echo 'selected="selected"'; ?> value="Arts:Fashion &amp; Beauty">Fashion &amp; Beauty</option>
                            <option <?php if (in_array('Arts:Food', $categories)) echo 'selected="selected"'; ?> value="Arts:Food">Food</option>
                            <option <?php if (in_array('Arts:Performing Arts', $categories)) echo 'selected="selected"'; ?> value="Arts:Performing Arts">Performing Arts</option>
                            <option <?php if (in_array('Arts:Visual Arts', $categories)) echo 'selected="selected"'; ?> value="Arts:Visual Arts">Visual Arts</option>
                        </optgroup>
                        <option <?php if (in_array('Business', $categories)) echo 'selected="selected"'; ?> value="Business">Business</option>
                        <optgroup label="">
                            <option <?php if (in_array('Business:Careers', $categories)) echo 'selected="selected"'; ?> value="Business:Careers">Careers</option>
                            <option <?php if (in_array('Business:Entrepreneurship', $categories)) echo 'selected="selected"'; ?> value="Business:Entrepreneurship">Entrepreneurship</option>
                            <option <?php if (in_array('Business:Investing', $categories)) echo 'selected="selected"'; ?> value="Business:Investing">Investing</option>
                            <option <?php if (in_array('Business:Management', $categories)) echo 'selected="selected"'; ?> value="Business:Management">Management</option>
                            <option <?php if (in_array('Business:Marketing', $categories)) echo 'selected="selected"'; ?> value="Business:Marketing">Marketing</option>
                            <option <?php if (in_array('Business:Non-Profit', $categories)) echo 'selected="selected"'; ?> value="Business:Non-Profit">Non-Profit</option>
                        </optgroup>
                        <option <?php if (in_array('Comedy', $categories)) echo 'selected="selected"'; ?> value="Comedy">Comedy</option>
                        <optgroup label="">
                            <option <?php if (in_array('Comedy:Comedy Interviews', $categories)) echo 'selected="selected"'; ?> value="Comedy:Comedy Interviews">Comedy Interviews</option>
                            <option <?php if (in_array('Comedy:Improv', $categories)) echo 'selected="selected"'; ?> value="Comedy:Improv">Improv</option>
                            <option <?php if (in_array('Comedy:Stand-Up', $categories)) echo 'selected="selected"'; ?> value="Comedy:Stand-Up">Stand-Up</option>
                        </optgroup>
                        <option <?php if (in_array('Education', $categories)) echo 'selected="selected"'; ?> value="Education">Education</option>
                        <optgroup label="">
                            <option <?php if (in_array('Education:Courses', $categories)) echo 'selected="selected"'; ?> value="Education:Courses">Courses</option>
                            <option <?php if (in_array('Education:How To', $categories)) echo 'selected="selected"'; ?> value="Education:How To">How To</option>
                            <option <?php if (in_array('Education:Language Learning', $categories)) echo 'selected="selected"'; ?> value="Education:Language Learning">Language Learning</option>
                            <option <?php if (in_array('Education:Self-Improvement', $categories)) echo 'selected="selected"'; ?> value="Education:Self-Improvement">Self-Improvement</option>
                        </optgroup>
                        <option <?php if (in_array('Government', $categories)) echo 'selected="selected"'; ?> value="Government">Government</option>
                        <option <?php if (in_array('History', $categories)) echo 'selected="selected"'; ?> value="History">History</option>
                        <option <?php if (in_array('Health & Fitness', $categories)) echo 'selected="selected"'; ?> value="Health &amp; Fitness">Health & Fitness</option>
                        <optgroup label="">
                            <option <?php if (in_array('Health & Fitness:Alternative Health', $categories)) echo 'selected="selected"'; ?> value="Health &amp; Fitness:Alternative Health">Alternative Health</option>
                            <option <?php if (in_array('Health & Fitness:Fitness', $categories)) echo 'selected="selected"'; ?> value="Health &amp; Fitness:Fitness">Fitness</option>
                            <option <?php if (in_array('Health & Fitness:Medicine', $categories)) echo 'selected="selected"'; ?> value="Health &amp; Fitness:Medicine">Medicine</option>
                            <option <?php if (in_array('Health & Fitness:Mental Health', $categories)) echo 'selected="selected"'; ?> value="Health &amp; Fitness:Mental Health">Mental Health</option>
                            <option <?php if (in_array('Health & Fitness:Nutrition', $categories)) echo 'selected="selected"'; ?> value="Health &amp; Fitness:Nutrition">Nutrition</option>
                            <option <?php if (in_array('Health & Fitness:Sexuality', $categories)) echo 'selected="selected"'; ?> value="Health &amp; Fitness:Sexuality">Sexuality</option>
                        </optgroup>
                        <option <?php if (in_array('Kids & Family', $categories)) echo 'selected="selected"'; ?> value="Kids &amp; Family">Kids &amp; Family</option>
                        <optgroup label="">
                            <option <?php if (in_array('Kids & Family:Education for Kids', $categories)) echo 'selected="selected"'; ?> value="Kids &amp; Family:Education for Kids">Education for Kids</option>
                            <option <?php if (in_array('Kids & Family:Parenting', $categories)) echo 'selected="selected"'; ?> value="Kids &amp; Family:Parenting">Parenting</option>
                            <option <?php if (in_array('Kids & Family:Pets & Animals', $categories)) echo 'selected="selected"'; ?> value="Kids &amp; Family:Pets &amp; Animals">Pets & Animals</option>
                            <option <?php if (in_array('Kids & Family:Stories for Kids', $categories)) echo 'selected="selected"'; ?> value="Kids &amp; Family:Stories for Kids">Stories for Kids</option>
                        </optgroup>
                        <option <?php if (in_array('Leisure', $categories)) echo 'selected="selected"'; ?> value="Leisure">Leisure</option>
                        <optgroup label="">
                            <option <?php if (in_array('Leisure:Animation & Manga', $categories)) echo 'selected="selected"'; ?> value="Leisure:Animation &amp; Manga">Animation & Manga</option>
                            <option <?php if (in_array('Leisure:Automotive', $categories)) echo 'selected="selected"'; ?> value="Leisure:Automotive">Automotive</option>
                            <option <?php if (in_array('Leisure:Aviation', $categories)) echo 'selected="selected"'; ?> value="Leisure:Aviation">Aviation</option>
                            <option <?php if (in_array('Leisure:Crafts', $categories)) echo 'selected="selected"'; ?> value="Leisure:Crafts">Crafts</option>
                            <option <?php if (in_array('Leisure:Games', $categories)) echo 'selected="selected"'; ?> value="Leisure:Games">Games</option>
                            <option <?php if (in_array('Leisure:Hobbies', $categories)) echo 'selected="selected"'; ?> value="Leisure:Hobbies">Hobbies</option>
                            <option <?php if (in_array('Leisure:Home & Garden', $categories)) echo 'selected="selected"'; ?> value="Leisure:Home &amp; Garden">Home & Garden</option>
                            <option <?php if (in_array('Leisure:Video Games', $categories)) echo 'selected="selected"'; ?> value="Leisure:Video Games">Video Games</option>
                        </optgroup>
                        <option <?php if (in_array('Music', $categories)) echo 'selected="selected"'; ?> value="Music">Music</option>
                        <optgroup label="">
                            <option <?php if (in_array('Music:Music Commentary', $categories)) echo 'selected="selected"'; ?> value="Music:Music Commentary">Music Commentary</option>
                            <option <?php if (in_array('Music:Music History', $categories)) echo 'selected="selected"'; ?> value="Music:Music History">Music History</option>
                            <option <?php if (in_array('Music:Music Interviews', $categories)) echo 'selected="selected"'; ?> value="Music:Music Interviews">Music Interviews</option>
                        </optgroup>
                        <option <?php if (in_array('News', $categories)) echo 'selected="selected"'; ?> value="News">News</option>
                        <optgroup label="">
                            <option <?php if (in_array('News:Business News', $categories)) echo 'selected="selected"'; ?> value="News:Business News">Business News</option>
                            <option <?php if (in_array('News:Daily News', $categories)) echo 'selected="selected"'; ?> value="News:Daily News">Daily News</option>
                            <option <?php if (in_array('News:Entertainment News', $categories)) echo 'selected="selected"'; ?> value="News:Entertainment News">Entertainment News</option>
                            <option <?php if (in_array('News:News Commentary', $categories)) echo 'selected="selected"'; ?> value="News:News Commentary">News Commentary</option>
                            <option <?php if (in_array('News:Politics', $categories)) echo 'selected="selected"'; ?> value="News:Politics">Politics</option>
                            <option <?php if (in_array('News:Sports News', $categories)) echo 'selected="selected"'; ?> value="News:Sports News">Sports News</option>
                            <option <?php if (in_array('News:Tech News', $categories)) echo 'selected="selected"'; ?> value="News:Tech News">Tech News</option>
                        </optgroup>
                        <option <?php if (in_array('Religion & Spirituality', $categories)) echo 'selected="selected"'; ?> value="Religion &amp; Spirituality">Religion &amp; Spirituality</option>
                        <optgroup label="">
                            <option <?php if (in_array('Religion & Spirituality:Buddhism', $categories)) echo 'selected="selected"'; ?> value="Religion &amp; Spirituality:Buddhism">Buddhism</option>
                            <option <?php if (in_array('Religion & Spirituality:Christianity', $categories)) echo 'selected="selected"'; ?> value="Religion &amp; Spirituality:Christianity">Christianity</option>
                            <option <?php if (in_array('Religion & Spirituality:Hinduism', $categories)) echo 'selected="selected"'; ?> value="Religion &amp; Spirituality:Hinduism">Hinduism</option>
                            <option <?php if (in_array('Religion & Spirituality:Islam', $categories)) echo 'selected="selected"'; ?> value="Religion &amp; Spirituality:Islam">Islam</option>
                            <option <?php if (in_array('Religion & Spirituality:Judaism', $categories)) echo 'selected="selected"'; ?> value="Religion &amp; Spirituality:Judaism">Judaism</option>
                            <option <?php if (in_array('Religion & Spirituality:Religion', $categories)) echo 'selected="selected"'; ?> value="Religion &amp; Spirituality:Religion">Religion</option>
                            <option <?php if (in_array('Religion & Spirituality:Spirituality', $categories)) echo 'selected="selected"'; ?> value="Religion &amp; Spirituality:Spirituality">Spirituality</option>
                        </optgroup>
                        <option <?php if (in_array('Science', $categories)) echo 'selected="selected"'; ?> value="Science">Science</option>
                        <optgroup label="">
                            <option <?php if (in_array('Science:Astronomy', $categories)) echo 'selected="selected"'; ?> value="Science:Astronomy">Astronomy</option>
                            <option <?php if (in_array('Science:Chemistry', $categories)) echo 'selected="selected"'; ?> value="Science:Chemistry">Chemistry</option>
                            <option <?php if (in_array('Science:Earth Sciences', $categories)) echo 'selected="selected"'; ?> value="Science:Earth Sciences">Earth Sciences</option>
                            <option <?php if (in_array('Science:Life Sciences', $categories)) echo 'selected="selected"'; ?> value="Science:Life Sciences">Life Sciences</option>
                            <option <?php if (in_array('Science:Mathematics', $categories)) echo 'selected="selected"'; ?> value="Science:Mathematics">Mathematics</option>
                            <option <?php if (in_array('Science:Natural Sciences', $categories)) echo 'selected="selected"'; ?> value="Science:Natural Sciences">Natural Sciences</option>
                            <option <?php if (in_array('Science:Nature', $categories)) echo 'selected="selected"'; ?> value="Science:Nature">Nature</option>
                            <option <?php if (in_array('Science:Physics', $categories)) echo 'selected="selected"'; ?> value="Science:Physics">Physics</option>
                            <option <?php if (in_array('Science:Social Sciences', $categories)) echo 'selected="selected"'; ?> value="Science:Social Sciences">Social Sciences</option>
                        </optgroup>
                        <option <?php if (in_array('Society & Culture', $categories)) echo 'selected="selected"'; ?> value="Society &amp; Culture">Society &amp; Culture</option>
                        <optgroup label="">
                            <option <?php if (in_array('Society & Culture:Documentary', $categories)) echo 'selected="selected"'; ?> value="Society &amp; Culture:Documentary">Documentary</option>
                            <option <?php if (in_array('Society & Culture:Personal Journals', $categories)) echo 'selected="selected"'; ?> value="Society &amp; Culture:Personal Journals">Personal Journals</option>
                            <option <?php if (in_array('Society & Culture:Philosophy', $categories)) echo 'selected="selected"'; ?> value="Society &amp; Culture:Philosophy">Philosophy</option>
                            <option <?php if (in_array('Society & Culture:Places & Travel', $categories)) echo 'selected="selected"'; ?> value="Society &amp; Culture:Places &amp; Travel">Places &amp; Travel</option>
                            <option <?php if (in_array('Society & Culture:Relationships', $categories)) echo 'selected="selected"'; ?> value="Society &amp; Culture:Relationships">Relationships</option>
                        </optgroup>
                        <option <?php if (in_array('Sports', $categories)) echo 'selected="selected"'; ?> value="Sports">Sports</option>
                        <optgroup label="">
                            <option <?php if (in_array('Sports:Baseball', $categories)) echo 'selected="selected"'; ?> value="Sports:Baseball">Baseball</option>
                            <option <?php if (in_array('Sports:Basketball', $categories)) echo 'selected="selected"'; ?> value="Sports:Basketball">Basketball</option>
                            <option <?php if (in_array('Sports:Cricket', $categories)) echo 'selected="selected"'; ?> value="Sports:Cricket">Cricket</option>
                            <option <?php if (in_array('Sports:Fantasy Sports', $categories)) echo 'selected="selected"'; ?> value="Sports:Fantasy Sports">Fantasy Sports</option>
                            <option <?php if (in_array('Sports:Football', $categories)) echo 'selected="selected"'; ?> value="Sports:Football">Football</option>
                            <option <?php if (in_array('Sports:Golf', $categories)) echo 'selected="selected"'; ?> value="Sports:Golf">Golf</option>
                            <option <?php if (in_array('Sports:Hockey', $categories)) echo 'selected="selected"'; ?> value="Sports:Hockey">Hockey</option>
                            <option <?php if (in_array('Sports:Rugby', $categories)) echo 'selected="selected"'; ?> value="Sports:Rugby">Rugby</option>
                            <option <?php if (in_array('Sports:Running', $categories)) echo 'selected="selected"'; ?> value="Sports:Running">Running</option>
                            <option <?php if (in_array('Sports:Soccer', $categories)) echo 'selected="selected"'; ?> value="Sports:Soccer">Soccer</option>
                            <option <?php if (in_array('Sports:Swimming', $categories)) echo 'selected="selected"'; ?> value="Sports:Swimming">Swimming</option>
                            <option <?php if (in_array('Sports:Tennis', $categories)) echo 'selected="selected"'; ?> value="Sports:Tennis">Tennis</option>
                            <option <?php if (in_array('Sports:Volleyball', $categories)) echo 'selected="selected"'; ?> value="Sports:Volleyball">Volleyball</option>
                            <option <?php if (in_array('Sports:Wilderness', $categories)) echo 'selected="selected"'; ?> value="Sports:Wilderness">Wilderness</option>
                            <option <?php if (in_array('Sports:Wrestling', $categories)) echo 'selected="selected"'; ?> value="Sports:Wrestling">Wrestling</option>
                        </optgroup>
                        <option <?php if (in_array('Technology', $categories)) echo 'selected="selected"'; ?> value="Technology">Technology</option>
                        <option <?php if (in_array('True Crime', $categories)) echo 'selected="selected"'; ?> value="True Crime">True Crime</option>
                        <option <?php if (in_array('TV & Film', $categories)) echo 'selected="selected"'; ?> value="TV &amp; Film">TV &amp; Film</option>
                        <optgroup label="">
                            <option <?php if (in_array('TV & Film:After Shows', $categories)) echo 'selected="selected"'; ?> value="TV &amp; Film:After Shows">After Shows</option>
                            <option <?php if (in_array('TV & Film:Film History', $categories)) echo 'selected="selected"'; ?> value="TV &amp; Film:Film History">Film History</option>
                            <option <?php if (in_array('TV & Film:Film Interviews', $categories)) echo 'selected="selected"'; ?> value="TV &amp; Film:Film Interviews">Film Interviews</option>
                            <option <?php if (in_array('TV & Film:Film Reviews', $categories)) echo 'selected="selected"'; ?> value="TV &amp; Film:Film Reviews">Film Reviews</option>
                            <option <?php if (in_array('TV & Film:TV Reviews', $categories)) echo 'selected="selected"'; ?> value="TV &amp; Film:TV Reviews">TV Reviews</option>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="dcf-form-group">
                <label for='itunes_explicit'>Explicit</label>
                <div class="dcf-popup dcf-d-inline" id="itune-explicit-details" data-hover="true" data-point="true">
                    <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                            viewBox="0 0 24 24"
                        >
                            <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23 C5.71,23,1,18.29,1,12.5
                                C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                            <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                                C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5 C8.224,19,8,19.224,8,19.5
                                S8.224,20,8.5,20h6c0.276,0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                            <circle cx="11" cy="6.5" r="1"></circle>
                            <g>
                                <path fill="none" d="M0 0H24V24H0z"></path>
                            </g>
                        </svg>
                    </button>
                    <div
                        class="
                            dcf-popup-content
                            unl-cream
                            unl-bg-blue
                            dcf-p-1
                            dcf-rounded
                        "
                        style="min-width: 25ch;"
                    >
                        <p class="dcf-m-0 dcf-regular">
                            Set to 'yes' if this feed contains explicit content
                        </p>
                    </div>
                </div>

                <div>
                    <input name="UNL_MediaHub_Feed_NamespacedElements_itunes[4][element]" type="hidden" value="explicit"/>
                    <select class="dcf-input-select" id="itunes_explicit" name="UNL_MediaHub_Feed_NamespacedElements_itunes[4][value]" aria-describedby="itune-explicit-details">
                        <?php
                        if (getFieldValue($context, 'itunes', 'explicit') == "yes") {
                            echo '<option value="no">No</option>
                            <option value="yes" selected="selected">Yes</option>';
                        } else {
                            echo '<option value="no">No</option>
                            <option value="yes">Yes</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="dcf-form-group">
                <label for='itunes_keywords'>Keywords</label>
                <div class="dcf-popup dcf-d-inline" id="itune-keyword-details" data-hover="true" data-point="true">
                    <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                            viewBox="0 0 24 24"
                        >
                            <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23 C5.71,23,1,18.29,1,12.5
                                C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                            <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                                C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5 C8.224,19,8,19.224,8,19.5
                                S8.224,20,8.5,20h6c0.276,0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                            <circle cx="11" cy="6.5" r="1"></circle>
                            <g>
                                <path fill="none" d="M0 0H24V24H0z"></path>
                            </g>
                        </svg>
                    </button>
                    <div
                        class="
                            dcf-popup-content
                            unl-cream
                            unl-bg-blue
                            dcf-p-1
                            dcf-rounded
                        "
                        style="min-width: 25ch;"
                    >
                        <p class="dcf-m-0 dcf-regular">
                            This tag allows users to search on a maximum of 12 text keywords.
                            Use commas to separate keywords.
                        </p>
                    </div>
                </div>

                <div>
                    <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[5][element]' type='hidden' value='keywords' />
                    <input id='itunes_keywords' name='UNL_MediaHub_Feed_NamespacedElements_itunes[5][value]' aria-describedby="itune-keyword-details" type='text' value='<?php echo getFieldValue($context, 'itunes', 'keywords'); ?>' size='55' />
                </div>
            </div>
            <div class="dcf-form-group">
                <label for='itunes_new-feed-url'>New-feed-url</label>
                <div class="dcf-popup dcf-d-inline" id="itune-new-feed-url-details" data-hover="true" data-point="true">
                    <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                            viewBox="0 0 24 24"
                        >
                            <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23 C5.71,23,1,18.29,1,12.5
                                C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                            <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                                C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5 C8.224,19,8,19.224,8,19.5
                                S8.224,20,8.5,20h6c0.276,0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                            <circle cx="11" cy="6.5" r="1"></circle>
                            <g>
                                <path fill="none" d="M0 0H24V24H0z"></path>
                            </g>
                        </svg>
                    </button>
                    <div
                        class="
                            dcf-popup-content
                            unl-cream
                            unl-bg-blue
                            dcf-p-1
                            dcf-rounded
                        "
                        style="min-width: 25ch;"
                    >
                        <p class="dcf-m-0 dcf-regular">
                            This tag allows you to change the URL where the podcast feed is located.
                        </p>
                    </div>
                </div>

                <div>
                    <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[6][element]' type='hidden' value='new-feed-url' />
                    <input id='itunes_new-feed-url' name='UNL_MediaHub_Feed_NamespacedElements_itunes[6][value]' aria-describedby="itune-new-feed-url-details" type='text' value='<?php echo getFieldValue($context, 'itunes', 'new-feed-url'); ?>' size='55' />
                </div>
            </div>
            <div class="dcf-form-group">
                <label for='itunes_owner'>Owner</label>
                <div class="dcf-popup dcf-d-inline" id="itunes-owner-details" data-hover="true" data-point="true">
                    <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                            viewBox="0 0 24 24"
                        >
                            <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23 C5.71,23,1,18.29,1,12.5
                                C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                            <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                                C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5 C8.224,19,8,19.224,8,19.5
                                S8.224,20,8.5,20h6c0.276,0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                            <circle cx="11" cy="6.5" r="1"></circle>
                            <g>
                                <path fill="none" d="M0 0H24V24H0z"></path>
                            </g>
                        </svg>
                    </button>
                    <div
                        class="
                            dcf-popup-content
                            unl-cream
                            unl-bg-blue
                            dcf-p-1
                            dcf-rounded
                        "
                        style="min-width: 25ch;"
                    >
                        <p class="dcf-m-0 dcf-regular">
                            Information that will be used to contact the owner of the podcast. Not publicly displayed.
                        </p>
                    </div>
                </div>

                <div>
                    <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[7][element]' type='hidden' value='owner' />
                    <input id='itunes_owner' name='UNL_MediaHub_Feed_NamespacedElements_itunes[7][value]' aria-describedby="itunes-owner-details" type='text' value='<?php echo getFieldValue($context, 'itunes', 'owner'); ?>' size='55' />
                </div>
            </div>
            <div class="dcf-form-group">
                <label for='itunes_subtitle'>Subtitle</label>
                <div class="dcf-popup dcf-d-inline" id="itunes-subtitle-details" data-hover="true" data-point="true">
                    <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                            viewBox="0 0 24 24"
                        >
                            <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23 C5.71,23,1,18.29,1,12.5
                                C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                            <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                                C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5 C8.224,19,8,19.224,8,19.5
                                S8.224,20,8.5,20h6c0.276,0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                            <circle cx="11" cy="6.5" r="1"></circle>
                            <g>
                                <path fill="none" d="M0 0H24V24H0z"></path>
                            </g>
                        </svg>
                    </button>
                    <div
                        class="
                            dcf-popup-content
                            unl-cream
                            unl-bg-blue
                            dcf-p-1
                            dcf-rounded
                        "
                        style="min-width: 25ch;"
                    >
                        <p class="dcf-m-0 dcf-regular">
                            The contents of this tag are shown in the Description column in iTunes. The
                            subtitle displays best if it is only a few words long.
                        </p>
                    </div>
                </div>
                <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[8][element]' type='hidden' value='subtitle' />
                <input id='itunes_subtitle' name='UNL_MediaHub_Feed_NamespacedElements_itunes[8][value]' aria-describedby="itunes-subtitle-details" type='text' value='<?php echo getFieldValue($context, 'itunes', 'subtitle'); ?>' size='55' />
            </div>
            <div class="dcf-form-group">
                <label for='itunes_summary'>Summary</label>
                <div class="dcf-popup dcf-d-inline" id="itunes-summary-details" data-hover="true" data-point="true">
                    <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                            viewBox="0 0 24 24"
                        >
                            <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23 C5.71,23,1,18.29,1,12.5
                                C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                            <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                                C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5 C8.224,19,8,19.224,8,19.5
                                S8.224,20,8.5,20h6c0.276,0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                            <circle cx="11" cy="6.5" r="1"></circle>
                            <g>
                                <path fill="none" d="M0 0H24V24H0z"></path>
                            </g>
                        </svg>
                    </button>
                    <div
                        class="
                            dcf-popup-content
                            unl-cream
                            unl-bg-blue
                            dcf-p-1
                            dcf-rounded
                        "
                        style="min-width: 25ch;"
                    >
                        <p class="dcf-m-0 dcf-regular">
                            The contents of this tag are shown in a separate window that appears when the
                            "circled ?" in the Description column is clicked. It also appears on the
                            iTunes page for your podcast.
                        </p>
                    </div>
                </div>
                <input name='UNL_MediaHub_Feed_NamespacedElements_itunes[9][element]' type='hidden' value='summary' />
                <textarea id="itunes_summary" name="UNL_MediaHub_Feed_NamespacedElements_itunes[9][value]" aria-describedby="itunes-summary-details" rows="5" cols="50"><?php echo getFieldValue($context, 'itunes', 'summary'); ?></textarea>
            </div>
        </fieldset>
        <fieldset id="media_header" class="dcf-mt-6">
            <legend class="dcf-legend">Media RSS Options</legend>
            <div class="dcf-form-group">
                <label for='media_rating'>Rating</label>
                <div class="dcf-popup dcf-d-inline" id="rating-details" data-hover="true" data-point="true">
                    <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                            viewBox="0 0 24 24"
                        >
                            <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23 C5.71,23,1,18.29,1,12.5
                                C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                            <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                                C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5 C8.224,19,8,19.224,8,19.5
                                S8.224,20,8.5,20h6c0.276,0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                            <circle cx="11" cy="6.5" r="1"></circle>
                            <g>
                                <path fill="none" d="M0 0H24V24H0z"></path>
                            </g>
                        </svg>
                    </button>
                    <div
                        class="
                            dcf-popup-content
                            unl-cream
                            unl-bg-blue
                            dcf-p-1
                            dcf-rounded
                        "
                        style="min-width: 25ch;"
                    >
                        <p class="dcf-m-0 dcf-regular">
                            Appropriate audience: adult or nonadult. Leave blank if no restrictions.
                        </p>
                    </div>
                </div>
                <div>
                    <input name='UNL_MediaHub_Feed_NamespacedElements_media[0][element]' type='hidden' value='rating' />
                    <input id='media_rating' name='UNL_MediaHub_Feed_NamespacedElements_media[0][value]' aria-describedby="rating-details" type='text' value='<?php echo getFieldValue($context, 'media', 'rating'); ?>' size='55' />
                </div>
            </div>
            <div class="dcf-form-group">
                <label for='media_title'>Title</label>
                <div>
                    <input name='UNL_MediaHub_Feed_NamespacedElements_media[1][element]' type='hidden' value='title' />
                    <input id='media_title' name='UNL_MediaHub_Feed_NamespacedElements_media[1][value]' type='text' value='<?php echo getFieldValue($context, 'media', 'title'); ?>' size='55' />
                </div>
            </div>
            <div class="dcf-form-group">
                <label for='media_description'>Description</label>
                <input name='UNL_MediaHub_Feed_NamespacedElements_media[2][element]' type='hidden' value='description' />
                <textarea id="media_description" name="UNL_MediaHub_Feed_NamespacedElements_media[2][value]" rows="5" cols="50"><?php echo getFieldValue($context, 'media', 'description'); ?></textarea>
            </div>
            <div class="dcf-form-group">
                <label for='media_keywords'>Keywords</label>
                <div class="dcf-popup dcf-d-inline" id="keyword-details" data-hover="true" data-point="true">
                    <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                            viewBox="0 0 24 24"
                        >
                            <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23 C5.71,23,1,18.29,1,12.5
                                C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                            <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                                C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5 C8.224,19,8,19.224,8,19.5
                                S8.224,20,8.5,20h6c0.276,0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                            <circle cx="11" cy="6.5" r="1"></circle>
                            <g>
                                <path fill="none" d="M0 0H24V24H0z"></path>
                            </g>
                        </svg>
                    </button>
                    <div
                        class="
                            dcf-popup-content
                            unl-cream
                            unl-bg-blue
                            dcf-p-1
                            dcf-rounded
                        "
                        style="min-width: 25ch;"
                    >
                        <p class="dcf-m-0 dcf-regular">
                            Comma seperated list of highly relevant keywords/tags describing the channel.
                        </p>
                    </div>
                </div>
                <div>
                    <input name='UNL_MediaHub_Feed_NamespacedElements_media[3][element]' type='hidden' value='keywords' />
                    <input id='media_keywords' name='UNL_MediaHub_Feed_NamespacedElements_media[3][value]' type='text' aria-describedby="keyword-details" value='<?php echo getFieldValue($context, 'media', 'keywords'); ?>' size='55' />
                </div>
            </div>
            <div class="dcf-form-group">
                <label for='media_thumbnail'>Thumbnail</label>
                <div class="dcf-popup dcf-d-inline" id="thumbnail-details" data-hover="true" data-point="true">
                    <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                            viewBox="0 0 24 24"
                        >
                            <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23 C5.71,23,1,18.29,1,12.5
                                C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                            <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                                C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5 C8.224,19,8,19.224,8,19.5
                                S8.224,20,8.5,20h6c0.276,0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                            <circle cx="11" cy="6.5" r="1"></circle>
                            <g>
                                <path fill="none" d="M0 0H24V24H0z"></path>
                            </g>
                        </svg>
                    </button>
                    <div
                        class="
                            dcf-popup-content
                            unl-cream
                            unl-bg-blue
                            dcf-p-1
                            dcf-rounded
                        "
                        style="min-width: 25ch;"
                    >
                        <p class="dcf-m-0 dcf-regular">
                            Allows particular images to be used as representative images for the media object.
                        </p>
                    </div>
                </div>
                <div>
                    <input name='UNL_MediaHub_Feed_NamespacedElements_media[4][element]' type='hidden' value='thumbnail' />
                    <input id='media_thumbnail' name='UNL_MediaHub_Feed_NamespacedElements_media[4][value]' aria-describedby="thumbnail-details" type='text' value='<?php echo getFieldValue($context, 'media', 'thumbnail'); ?>' size='55' />
                </div>
            </div>
            <div class="dcf-form-group">
                <label for='media_category'>Category</label>
                <div class="dcf-popup dcf-d-inline" id="category-details" data-hover="true" data-point="true">
                    <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                            viewBox="0 0 24 24"
                        >
                            <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23 C5.71,23,1,18.29,1,12.5
                                C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                            <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                                C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5 C8.224,19,8,19.224,8,19.5
                                S8.224,20,8.5,20h6c0.276,0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                            <circle cx="11" cy="6.5" r="1"></circle>
                            <g>
                                <path fill="none" d="M0 0H24V24H0z"></path>
                            </g>
                        </svg>
                    </button>
                    <div
                        class="
                            dcf-popup-content
                            unl-cream
                            unl-bg-blue
                            dcf-p-1
                            dcf-rounded
                        "
                        style="min-width: 25ch;"
                    >
                        <p class="dcf-m-0 dcf-regular">
                            Allows a taxonomy to be set that gives an indication of the type of media content,
                            and its particular contents
                        </p>
                    </div>
                </div>
                <div>
                    <input name='UNL_MediaHub_Feed_NamespacedElements_media[5][element]' type='hidden' value='category' />
                    <input id='media_category' name='UNL_MediaHub_Feed_NamespacedElements_media[5][value]' aria-describedby="category-details" type='text' value='<?php echo getFieldValue($context, 'media', 'category'); ?>' size='55' />
                </div>
            </div>
            <div class="dcf-form-group">
                <label for='media_player'>Player</label>
                <div class="dcf-popup dcf-d-inline" id="player-details" data-hover="true" data-point="true">
                    <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                            viewBox="0 0 24 24"
                        >
                            <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23 C5.71,23,1,18.29,1,12.5
                                C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                            <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                                C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5 C8.224,19,8,19.224,8,19.5
                                S8.224,20,8.5,20h6c0.276,0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                            <circle cx="11" cy="6.5" r="1"></circle>
                            <g>
                                <path fill="none" d="M0 0H24V24H0z"></path>
                            </g>
                        </svg>
                    </button>
                    <div
                        class="
                            dcf-popup-content
                            unl-cream
                            unl-bg-blue
                            dcf-p-1
                            dcf-rounded
                        "
                        style="min-width: 25ch;"
                    >
                        <p class="dcf-m-0 dcf-regular">
                            Allows the media object to be accessed through a web browser media player console.
                        </p>
                    </div>
                </div>
                <div>
                    <input name='UNL_MediaHub_Feed_NamespacedElements_media[6][element]' type='hidden' value='player' />
                    <input id='media_player' name='UNL_MediaHub_Feed_NamespacedElements_media[6][value]' aria-describedby="player-details" type='text' value='<?php echo getFieldValue($context, 'media', 'player'); ?>' size='55' />
                </div>
            </div>
            <div class="dcf-form-group">
                <label for='media_credit'>Credit</label>
                <div class="dcf-popup dcf-d-inline" id="credit-details" data-hover="true" data-point="true">
                    <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                            viewBox="0 0 24 24"
                        >
                            <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23 C5.71,23,1,18.29,1,12.5
                                C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                            <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                                C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5 C8.224,19,8,19.224,8,19.5
                                S8.224,20,8.5,20h6c0.276,0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                            <circle cx="11" cy="6.5" r="1"></circle>
                            <g>
                                <path fill="none" d="M0 0H24V24H0z"></path>
                            </g>
                        </svg>
                    </button>
                    <div
                        class="
                            dcf-popup-content
                            unl-cream
                            unl-bg-blue
                            dcf-p-1
                            dcf-rounded
                        "
                        style="min-width: 25ch;"
                    >
                        <p class="dcf-m-0 dcf-regular">
                            Notable entity and the contribution to the creation of the media object.
                        </p>
                    </div>
                </div>
                <div>
                    <input name='UNL_MediaHub_Feed_NamespacedElements_media[7][element]' type='hidden' value='credit' />
                    <input id='media_credit' name='UNL_MediaHub_Feed_NamespacedElements_media[7][value]' aria-describedby="credit-details" type='text' value='<?php echo getFieldValue($context, 'media', 'credit'); ?>' size='55' />
                </div>
            </div>
            <div class="dcf-form-group">
                <label for='media_copyright'>Copyright</label>
                <div class="dcf-popup dcf-d-inline" id="copyright-details" data-hover="true" data-point="true">
                    <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                            viewBox="0 0 24 24"
                        >
                            <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23 C5.71,23,1,18.29,1,12.5
                                C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                            <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                                C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5 C8.224,19,8,19.224,8,19.5
                                S8.224,20,8.5,20h6c0.276,0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                            <circle cx="11" cy="6.5" r="1"></circle>
                            <g>
                                <path fill="none" d="M0 0H24V24H0z"></path>
                            </g>
                        </svg>
                    </button>
                    <div
                        class="
                            dcf-popup-content
                            unl-cream
                            unl-bg-blue
                            dcf-p-1
                            dcf-rounded
                        "
                        style="min-width: 25ch;"
                    >
                        <p class="dcf-m-0 dcf-regular">
                            Copyright information for media object.
                        </p>
                    </div>
                </div>
                <div>
                    <input name='UNL_MediaHub_Feed_NamespacedElements_media[8][element]' type='hidden' value='copyright' />
                    <input id='media_copyright' name='UNL_MediaHub_Feed_NamespacedElements_media[8][value]' aria-describedby="copyright-details" type='text' value='<?php echo getFieldValue($context, 'media', 'copyright'); ?>' size='55' />
                </div>
            </div>
            <div class="dcf-form-group" style="display:none;">
                <label for='media_text'>Text</label>
                <div class="dcf-popup dcf-d-inline" id="text-details" data-hover="true" data-point="true">
                    <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                            viewBox="0 0 24 24"
                        >
                            <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23 C5.71,23,1,18.29,1,12.5
                                C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                            <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                                C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5 C8.224,19,8,19.224,8,19.5
                                S8.224,20,8.5,20h6c0.276,0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                            <circle cx="11" cy="6.5" r="1"></circle>
                            <g>
                                <path fill="none" d="M0 0H24V24H0z"></path>
                            </g>
                        </svg>
                    </button>
                    <div
                        class="
                            dcf-popup-content
                            unl-cream
                            unl-bg-blue
                            dcf-p-1
                            dcf-rounded
                        "
                        style="min-width: 25ch;"
                    >
                        <p class="dcf-m-0 dcf-regular">
                            Allows the inclusion of a text transcript, closed captioning, or lyrics
                            of the media content.
                        </p>
                    </div>
                </div>
                <div>
                    <input name='UNL_MediaHub_Feed_NamespacedElements_media[9][element]' type='hidden' value='text' />
                    <input id='media_text' name='UNL_MediaHub_Feed_NamespacedElements_media[9][value]' aria-describedby="text-details" type='text' value='<?php echo getFieldValue($context, 'media', 'text'); ?>' size='55' />
                </div>
            </div>
            <div class="dcf-form-group">
                <label for='media_restriction'>Restriction</label>
                <div class="dcf-popup dcf-d-inline" id="restriction-details" data-hover="true" data-point="true">
                    <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                            viewBox="0 0 24 24"
                        >
                            <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                                S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23 C5.71,23,1,18.29,1,12.5
                                C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                            <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                                C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5 C8.224,19,8,19.224,8,19.5
                                S8.224,20,8.5,20h6c0.276,0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                            <circle cx="11" cy="6.5" r="1"></circle>
                            <g>
                                <path fill="none" d="M0 0H24V24H0z"></path>
                            </g>
                        </svg>
                    </button>
                    <div
                        class="
                            dcf-popup-content
                            unl-cream
                            unl-bg-blue
                            dcf-p-1
                            dcf-rounded
                        "
                        style="min-width: 25ch;"
                    >
                        <p class="dcf-m-0 dcf-regular">
                        Allows restrictions to be placed on the aggregator rendering the media in the feed.
                        </p>
                    </div>
                </div>
                <div>
                    <input name='UNL_MediaHub_Feed_NamespacedElements_media[10][element]' type='hidden' value='restriction' />
                    <input id='media_restriction' name='UNL_MediaHub_Feed_NamespacedElements_media[10][value]' aria-describedby="restriction-details" type='text' value='<?php echo getFieldValue($context, 'media', 'restriction'); ?>' size='55' />
                </div>
            </div>
            <div class="dcf-form-group">
                <div>
                    <input type="submit" name="submit" id="save-channel-btn" value="Save" class="dcf-btn dcf-btn-primary dcf-float-left" />
                    <?php if (isset($context->feed)): ?>
                        <a href="#" class="dcf-btn dcf-btn-primary" id="deleteFormBtn">Delete</a>
                    <?php endif; ?>
                </div>
            </div>
        </fieldset>
    </form>
</div>
<script>
  document.getElementById('feed').addEventListener('submit', function(e) {

    var submitBtn = document.getElementById('save-channel-btn');
    submitBtn.setAttribute('disabled', 'disabled');

    var deleteBtn = document.getElementById('deleteFormBtn');
    deleteBtn.setAttribute('disabled', 'disabled');

    var errors = [];

    // Validate Title
    var title = document.getElementById('title').value.trim();
    if (!title) {
      errors.push('Title is required.');
    }

    // Validate Description
    var description = document.getElementById('description').value.trim();
    if (!description) {
      errors.push('Description is required.');
    }

    // Submit form or display errors
    if (errors.length > 0) {
      e.preventDefault();
      var errorsContainer = document.getElementById('media-errors');
      var errorsList = document.getElementById('media-errors-list');
      if (errorsList) {
        errorsList.innerHTML = '';
        for (var i = 0; i < errors.length; i++) {
          var errorItem = document.createElement('li');
          errorItem.innerHTML = errors[i];
          errorsList.appendChild(errorItem);
        }
      }
      submitBtn.removeAttribute('disabled');
      deleteBtn.removeAttribute('disabled');
      errorsContainer.style.display = 'block';
      errorsContainer.scrollIntoView();
    }
  });
</script>
<?php if (isset($context->feed)): ?>
    <?php echo $savvy->render($context->feed, 'Feed/DeleteForm.tpl.php'); ?>
    <script>
      var deleteFormBtn = document.getElementById('deleteFormBtn');
      deleteFormBtn.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('deleteForm').submit();
      });
    </script>
<?php endif; ?>
