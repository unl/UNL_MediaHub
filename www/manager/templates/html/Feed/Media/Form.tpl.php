<?php
$page->addScript(UNL_MediaHub_Controller::getURL() . 'templates/html/scripts/plupload/plupload.full.min.js');
$page->addScript(UNL_MediaHub_Controller::getURL() . 'templates/html/scripts/uploadScript.js');
$baseUrl = UNL_MediaHub_Controller::getURL();
$formView  = '';
$mediaType = 'audio';

if (isset($context->media)) {
//if we have media (we're editing) show the appropriate part of the form
    if ($context->media->isVideo()) {
        $mediaType = 'video';
    }
    $formView .= 'edit';
}

$edit_caption_url = UNL_MediaHub_Manager::getURL() . '?view=editcaptions&id=' . $context->media->id;

$js = '<script type="text/javascript">
var formView = "'.$formView.'";
var mediaType = "'.$mediaType.'";
</script>
<script type="text/javascript" src="'.UNL_MediaHub_Controller::getURL().'templates/html/scripts/mediaDetails.js"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
';

$controller->setReplacementData('head', $js);
?>

<?php if(empty($context->media->media_text_tracks_id)): ?>
<div class="wdn_notice alert mh-caption-alert">
    <div class="close">
        <a href="#" title="Close this notice">Close this notice</a>
    </div>
    <div class="message">
        <h4>This Video is Missing Captions!</h4>
        <div class="mh-caption-band">
            <p>
                For accessibility reasons, captions are required for <strong>all</strong> videos.
            </p>
            <p>
                <a class="wdn-button" href="<?php echo $edit_caption_url ?>">Caption Your Video</a>
            </p>
        </div>
    </div>
</div>

<script type="text/javascript">
WDN.initializePlugin('notice');
</script>

<?php endif; ?>

<form action="?" method="post" name="media_form" id="media_form" class="wdn-band" enctype="multipart/form-data">

    <input id="media_url" name="url" type="hidden" value="<?php echo htmlentities($context->media->url, ENT_QUOTES); ?>" />
    <input type="hidden" name="__unlmy_posttarget" value="feed_media" />
    <input type="hidden" id="id" name="id" value="<?php echo $context->media->id ?>" />
    
    <div class="wdn-band wdn-light-triad-band mediahub-embed">
        <div class="wdn-inner-wrapper">
            <div class="wdn-grid-set" id="headline_main">
                <div class="wdn-col-full">
                    <input type="submit" name="submit" value="Save" class="wdn-pull-right" />
                    <h1 class="clear-top wdn-brand"><div class="wdn-subhead">Edit Media Details for</div> <?php echo $context->media->title ?></h1>
                </div>  
                    <?php
                    if (isset($context->media)) {
                        echo $savvy->render($context->media, 'Media/Preview.tpl.php');
                    }
                    ?>
            </div>
        </div>
    </div>
    
    <div class="wdn-band mh-edit-media">
        <div class="wdn-inner-wrapper">
            <div class="wdn-grid-set">
                <div class="bp2-wdn-col-two-sevenths wdn-pull-right">
                    <ol>
                        <li>                        
                            <div id="mh_upload_media_container">
                                <div id="mh_upload_media" class="mh-upload-box mh-upload-box-small wdn-center">
                                    <object type="image/svg+xml" data="<?php echo $baseUrl; ?>/templates/html/css/images/swap-arrows.svg">
                                        <img src="<?php echo $baseUrl; ?>/templates/html/css/images/swap-arrows.png" alt="browse media">
                                    </object>
                                    <h2><span class="wdn-subhead">Swap Media</span></h2>
                                    <p>Upload a new .mp4 or .mp3 file and replace your old one. <strong class="wdn-icon-attention">(Caution: This deletes your old file.)</strong></p>
                                    
                                </div>
                                <div id="filelist" class="mh-upload-box wdn-center">
                                    Your browser doesn't have Flash, Silverlight or HTML5 support.
                                </div>
                            </div>
                        </li>
                        <li>
                            <?php echo $savvy->render($context, 'Feed/Media/fields/privacy.tpl.php'); ?>
                        </li>
                        <li>
                            <?php echo $savvy->render($context->feed_selection); ?>
                        </li>
                        <li>
                            <label for="media_poster">URL of custom poster image</label>
                            <div class="wdn-icon-info hang-left mh-tooltip italic" id="poster-details">
                                <div>
                                    <p>
                                        <?php
                                        $text = '';
                                        if (isset($context->media) && $context->media->isVideo()) {
                                            $text = 'This image will override the one chosen above.';
                                        }
                                        ?>
                                        If filled in, this image will be displayed as the thumbnail for the media.  <?php echo $text; ?>
                                    </p>
                                </div>
                            </div>
                            <input id="media_poster" name="poster" type="text" class="validate-url" value="<?php echo htmlentities(@$context->media->poster, ENT_QUOTES); ?>" aria-describedby="poster-details" />
                        </li>
                        <li>
                            <?php if (isset($context->media)): ?>
                            <a class="wdn-button wdn-button-brand" href="<?php echo $edit_caption_url ?>">Order/Edit Captions</a>
                            <?php endif; ?>
                        </li>
                    </ol>  
                </div>


                <div class="bp2-wdn-col-five-sevenths">
                    <fieldset id="existing_media">
                        <legend>Basic Information</legend>

                        <div class="validation-container">
                            <label for="title" class="element">
                                Title<span class="required">*</span>
                            </label>
                            <input id="title" name="title" type="text" class="required-entry" value="<?php echo htmlentities(@$context->media->title, ENT_QUOTES); ?>" />
                        </div>
                        
                        <div class="wdn-grid-set">
                            <div class="bp2-wdn-col-one-half">
                                <ol>
                                    <li>
                                        <label for="author" class="element">
                                            Author<span class="required">*</span><span class="helper"> Name of media creator.</span>
                                        </label>
                                        <div class="element">
                                            <input id="author" name="author" class="required-entry" type="text" value="<?php echo htmlentities(@$context->media->author, ENT_QUOTES); ?>" />
                                        </div>
                                    </li>
                                    <li>
                                        <label for="mrss_copyright" class="element">Copyright<span class="helper"> Copyright information for media object.</span></label>
                                        <div class="element">
                                            <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[10][element]" type="hidden" value="copyright"/>
                                            <input id="mrss_copyright" name="UNL_MediaHub_Feed_Media_NamespacedElements_media[10][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'copyright'); ?>"/>
                                        </div>
                                    </li>
                                </ol>
                            </div>           
                            <div class="bp2-wdn-col-one-half">
                                <ol>
                                    <li>
                                        <label for="mrss_credit" class="element">
                                            Credit 
                                        </label>
                                        <div class="wdn-icon-info hang-right mh-tooltip italic" id="credit-details">
                                            <div>
                                                <p>
                                                    Notable entity and the contribution to the creation of the media object.
                                                </p>
                                            </div>
                                        </div>
                                        <div class="element">
                                            <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[9][element]" type="hidden" value="credit"/>
                                            <input id="mrss_credit" name="UNL_MediaHub_Feed_Media_NamespacedElements_media[9][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'credit'); ?>" aria-describedby="credit-details" />
                                        </div>
                                    </li>
                                    <li>
                                        <label for="mrss_category" class="element">Category</label>
                                        <div class="wdn-icon-info hang-right mh-tooltip italic" id="category-details">
                                            <div>
                                                <p>
                                                    Allows a taxonomy to be set that gives an indication of the type of media content, and its particular contents.
                                                </p>
                                            </div>
                                        </div>
                                        <div class="element">
                                            <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[7][element]" type="hidden" value="category"/>
                                            <input id="mrss_category" name="UNL_MediaHub_Feed_Media_NamespacedElements_media[7][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'category'); ?>" aria-describedby="category-details"/>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                        </div>

                        <ol>
                            <li>
                                <label for="description" class="element">
                                    Description<span class="required">*</span>
                                </label>
                                <div class="mh-tooltip wdn-icon-info italic" id="description-details">
                                    <div>
                                        <p>Explain what this media is all about. Use a few sentences, but keep it to 1 paragraph.</p>
                                    </div>
                                </div>
                                <div class="element" id="description_wrapper"><textarea id="description" name="description" class="required-entry" rows="5" aria-describedby="description-details"><?php echo htmlentities(@$context->media->description); ?></textarea></div>
                            </li>

                            <li>
                                <label for="itunes_keywords" class="element">Tags</label>
                                <div class="mh-tooltip wdn-icon-info italic hang-right" id="tag-details">
                                    <div>
                                        <p>A comma separated list of highly relevant keywords, MAX 10. Tags also serve as iTunes Keywords.</p>
                                    </div>
                                </div>
                                <div class="element">
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[4][element]" type="hidden" value="keywords"/>
                                    <input id="itunes_keywords" name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[4][value]" type="text" value="<?php echo getFieldValue($context, 'itunes', 'keywords'); ?>" aria-describedby="tag-details"/>
                                </div>
                            </li>

                        </ol>
                    </fieldset>
                    <fieldset id="geo_location" class="collapsible">
                        <legend>Geo Location</legend>
                        <ol>
                            <li>
                                <label for="geo_lat" class="element">Latitude</label>
                                <div class="element">
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_geo[0][element]" type="hidden" value="lat"/>
                                    <input id="geo_lat" name="UNL_MediaHub_Feed_Media_NamespacedElements_geo[0][value]" class='geo_lat' type="text" value="<?php echo getFieldValue($context, 'geo', 'lat'); ?>"/>
                                </div>
                            </li>
                            <li>
                                <label for="geo_long" class="element">Longitude</label>
                                <div class="element">
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_geo[1][element]" type="hidden" value="long"/>
                                    <input id="geo_long" name="UNL_MediaHub_Feed_Media_NamespacedElements_geo[1][value]" class='geo_long' type="text" value="<?php echo getFieldValue($context, 'geo', 'long'); ?>"/>
                                </div>
                            </li>
                            <li>
                                <div id="map_canvas" style="width:500px;height:300px;"></div>
                            </li>
                        </ol>
                    </fieldset>

                    <?php $customFields = UNL_MediaHub_Feed_Media_NamespacedElements_mediahub::getCustomElements(); ?>
                    <fieldset class='collapsible' id="other_header">
                        <legend>Other Information</legend>
                        <ol>
                            <?php foreach ($customFields as $customField=>$description): ?>
                                <li><?php echo $savvy->render($context, 'Feed/Media/NamespacedElements/mediahub/'.$customField.'.tpl.php'); ?></li>
                            <?php endforeach; ?>
                            <li style="display:none;">
                                <label for="mrss_group" class="element">Group</label>
                                <div class="element">
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[0][element]" type="hidden" value="group"/>
                                    <input id="mrss_group" name="UNL_MediaHub_Feed_Media_NamespacedElements_media[0][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'group'); ?>"/>
                                </div>
                            </li>
                            <li style="display:none">
                                <!-- mrss hidden elements that are handled automatically -->
                                <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[1][element]" type="hidden" value="content" />
                                <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[1][value]" type="hidden" value="<?php echo getFieldValue($context, 'media', 'content'); ?>" />
                                <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[1][attributes]" type="hidden" value="<?php echo getFieldAttributes($context, 'media', 'content'); ?>" />
                            </li>
                            <li style="display:none;">
                                <label for="mrss_rating" class="element">Rating</label>
                                <div class="element">
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[2][element]" type="hidden" value="rating"/>
                                    <input id="mrss_rating" name="UNL_MediaHub_Feed_Media_NamespacedElements_media[2][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'rating'); ?>"/>
                                </div>
                            </li>
                            <li style="display:none;">
                                <label for="mrss_title" class="element">Title</label>
                                <div class="element">
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[3][element]" type="hidden" value="title"/>
                                    <input id="mrss_title" name="UNL_MediaHub_Feed_Media_NamespacedElements_media[3][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'title'); ?>"/>
                                </div>
                            </li>
                            <li style="display:none;">
                                <label for="mrss_description" class="element">Description</label>
                                <div class="element">
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[4][element]" type="hidden" value="description"/>
                                    <input id="mrss_description" name="UNL_MediaHub_Feed_Media_NamespacedElements_media[4][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'description'); ?>"/>
                                </div>
                            </li>
                            <li style="display:none;">
                                <label for="mrss_keywords" class="element">Keywords</label>
                                <div class="element">
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[5][element]" type="hidden" value="keywords"/>
                                    <input id="mrss_keywords" name="UNL_MediaHub_Feed_Media_NamespacedElements_media[5][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'keywords'); ?>"/>
                                </div>
                            </li>
                            <li style="display:none;">
                                <!-- mrss hidden elements that are handled automatically -->
                                <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[6][element]" type="hidden" value="thumbnail"/>
                                <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[6][value]" type="hidden" value="<?php echo getFieldValue($context, 'media', 'thumbnail'); ?>" />
                                <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[6][attributes]" type="hidden" value="<?php echo getFieldAttributes($context, 'media', 'thumbnail'); ?>" />
                            </li>

                            <li style="display:none;">
                                <label for="mrss_player" class="element">Player<span class="helper">Allows the media object to be accessed through a web browser media player console.</span></label>
                                <div class="element">
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[8][element]" type="hidden" value="player"/>
                                    <input id="mrss_player" name="UNL_MediaHub_Feed_Media_NamespacedElements_media[8][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'player'); ?>"/>
                                </div>
                            </li>


                            <li style="display:none;">
                                <label for="mrss_restriction" class="element">Restriction<span class="helper">Allows restrictions to be placed on the aggregator rendering the media in the feed.</span></label>
                                <div class="element">
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[12][element]" type="hidden" value="restriction"/>
                                    <input id="mrss_restriction" name="UNL_MediaHub_Feed_Media_NamespacedElements_media[12][value]" type="text" value="<?php echo getFieldValue($context, 'media', 'restriction'); ?>"/>
                                </div>
                            </li>
                        </ol>
                    </fieldset>

                    <fieldset class='collapsible'>
                        <legend>iTunes Information</legend>
                        <ol>
                            <li style="display:none;">
                                <label for="itunes_author" class="element">Author<span class="helper">Name of media creator.</span></label>
                                <input name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[0][element]" type="hidden" value="author"/>
                                <input id="itunes_author" name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[0][value]" type="text" value="<?php echo getFieldValue($context, 'itunes', 'author'); ?>"/>
                            </li>
                            <li>
                                <label for="mrss_text" class="element">
                                    Transcript/Captioning
                                </label>
                                <div class="mh-tooltip wdn-icon-info italic" id="captioning-details">
                                    <div>
                                        <p>Allows the inclusion of a text transcript, closed captioning, or lyrics of the media content.</p>
                                    </div>
                                </div>
                                <div class="element">
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_media[11][element]" type="hidden" value="text"/>
                                    <textarea rows="3" id="mrss_text" name="UNL_MediaHub_Feed_Media_NamespacedElements_media[11][value]" aria-describedby="captioning-details"><?php echo getFieldValue($context, 'media', 'text'); ?></textarea>
                                </div>
                            </li>
                            <li>
                                <label for="itunes_category" class="element">Category <span class="helper">Choose a category for use within iTunes U</span></label>
                                <div class="element">
                                    <?php
                                    $category = '';
                                    if (isset($context->media) && $value = UNL_MediaHub_Feed_Media_NamespacedElements_itunesu::mediaHasElement($context->media->id, 'category', 'itunesu')) {
                                        $category = $value['attributes']['itunesu:code'];
                                    }
                                    ?>
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_itunesu[0][element]" type="hidden" value="category" />
                                    <select id="itunes_category" name="UNL_MediaHub_Feed_Media_NamespacedElements_itunesu[0][attributes]">
                                        <option value="">None</option>
                                        <optgroup label="Business">
                                            <option <?php if ($category == '100') echo 'selected="selected"'; ?> value="100">Business</option>
                                            <option <?php if ($category == '100100') echo 'selected="selected"'; ?> value="100100">Economics</option>
                                            <option <?php if ($category == '100101') echo 'selected="selected"'; ?> value="100101">Finance</option>
                                            <option <?php if ($category == '100102') echo 'selected="selected"'; ?> value="100102">Hospitality</option>
                                            <option <?php if ($category == '100103') echo 'selected="selected"'; ?> value="100103">Management</option>
                                            <option <?php if ($category == '100104') echo 'selected="selected"'; ?> value="100104">Marketing</option>
                                            <option <?php if ($category == '100105') echo 'selected="selected"'; ?> value="100105">Personal Finance</option>
                                            <option <?php if ($category == '100106') echo 'selected="selected"'; ?> value="100106">Real Estate</option>
                                        </optgroup>
                                        <optgroup label="Engineering">
                                            <option <?php if ($category == '101') echo 'selected="selected"'; ?> value="101">Engineering</option>
                                            <option <?php if ($category == '101100') echo 'selected="selected"'; ?> value="101100">Chemical &amp; Petroleum</option>
                                            <option <?php if ($category == '101101') echo 'selected="selected"'; ?> value="101101">Civil</option>
                                            <option <?php if ($category == '101102') echo 'selected="selected"'; ?> value="101102">Computer Science</option>
                                            <option <?php if ($category == '101103') echo 'selected="selected"'; ?> value="101103">Electrical</option>
                                            <option <?php if ($category == '101104') echo 'selected="selected"'; ?> value="101104">Environmental</option>
                                            <option <?php if ($category == '101105') echo 'selected="selected"'; ?> value="101105">Mechanical</option>
                                        </optgroup>
                                        <optgroup label="Fine Arts">
                                            <option <?php if ($category == '102') echo 'selected="selected"'; ?> value="102">Fine Arts</option>
                                            <option <?php if ($category == '102100') echo 'selected="selected"'; ?> value="102100">Architecture</option>
                                            <option <?php if ($category == '102101') echo 'selected="selected"'; ?> value="102101">Art</option>
                                            <option <?php if ($category == '102102') echo 'selected="selected"'; ?> value="102102">Art History</option>
                                            <option <?php if ($category == '102103') echo 'selected="selected"'; ?> value="102103">Dance</option>
                                            <option <?php if ($category == '102104') echo 'selected="selected"'; ?> value="102104">Film</option>
                                            <option <?php if ($category == '102105') echo 'selected="selected"'; ?> value="102105">Graphic Design</option>
                                            <option <?php if ($category == '102106') echo 'selected="selected"'; ?> value="102106">Interior Design</option>
                                            <option <?php if ($category == '102107') echo 'selected="selected"'; ?> value="102107">Music</option>
                                            <option <?php if ($category == '102108') echo 'selected="selected"'; ?> value="102108">Theater</option>
                                        </optgroup>
                                        <optgroup label="Health &amp; Medicine">
                                            <option <?php if ($category == '103') echo 'selected="selected"'; ?> value="103">Health &amp; Medicine</option>
                                            <option <?php if ($category == '103100') echo 'selected="selected"'; ?> value="103100">Anatomy &amp; Physiology</option>
                                            <option <?php if ($category == '103101') echo 'selected="selected"'; ?> value="103101">Behavioral Science</option>
                                            <option <?php if ($category == '103102') echo 'selected="selected"'; ?> value="103102">Dentistry</option>
                                            <option <?php if ($category == '103103') echo 'selected="selected"'; ?> value="103103">Diet &amp; Nutrition</option>
                                            <option <?php if ($category == '103104') echo 'selected="selected"'; ?> value="103104">Emergency</option>
                                            <option <?php if ($category == '103105') echo 'selected="selected"'; ?> value="103105">Genetics</option>
                                            <option <?php if ($category == '103106') echo 'selected="selected"'; ?> value="103106">Gerontology</option>
                                            <option <?php if ($category == '103107') echo 'selected="selected"'; ?> value="103107">Health &amp; Exercise Science</option>
                                            <option <?php if ($category == '103108') echo 'selected="selected"'; ?> value="103108">Immunology</option>
                                            <option <?php if ($category == '103109') echo 'selected="selected"'; ?> value="103109">Neuroscience</option>
                                            <option <?php if ($category == '103110') echo 'selected="selected"'; ?> value="103110">Pharmacology &amp; Toxicology</option>
                                            <option <?php if ($category == '103111') echo 'selected="selected"'; ?> value="103111">Psychiatry</option>
                                            <option <?php if ($category == '103112') echo 'selected="selected"'; ?> value="103112">Public Health</option>
                                            <option <?php if ($category == '103113') echo 'selected="selected"'; ?> value="103113">Radiology</option>
                                        </optgroup>
                                        <optgroup label="History">
                                            <option <?php if ($category == '104') echo 'selected="selected"'; ?> value="104">History</option>
                                            <option <?php if ($category == '104100') echo 'selected="selected"'; ?> value="104100">Ancient</option>
                                            <option <?php if ($category == '104101') echo 'selected="selected"'; ?> value="104101">Medieval</option>
                                            <option <?php if ($category == '104102') echo 'selected="selected"'; ?> value="104102">Military</option>
                                            <option <?php if ($category == '104103') echo 'selected="selected"'; ?> value="104103">Modern</option>
                                            <option <?php if ($category == '104104') echo 'selected="selected"'; ?> value="104104">African</option>
                                            <option <?php if ($category == '104105') echo 'selected="selected"'; ?> value="104105">Asian</option>
                                            <option <?php if ($category == '104106') echo 'selected="selected"'; ?> value="104106">European</option>
                                            <option <?php if ($category == '104107') echo 'selected="selected"'; ?> value="104107">Middle Eastern</option>
                                            <option <?php if ($category == '104108') echo 'selected="selected"'; ?> value="104108">North American</option>
                                            <option <?php if ($category == '104109') echo 'selected="selected"'; ?> value="104109">South American</option>
                                        </optgroup>
                                        <optgroup label="Humanities">
                                            <option <?php if ($category == '105') echo 'selected="selected"'; ?> value="105">Humanities</option>
                                            <option <?php if ($category == '105100') echo 'selected="selected"'; ?> value="105100">Communications</option>
                                            <option <?php if ($category == '105101') echo 'selected="selected"'; ?> value="105101">Philosophy</option>
                                            <option <?php if ($category == '105102') echo 'selected="selected"'; ?> value="105102">Religion</option>
                                        </optgroup>
                                        <optgroup label="Language">
                                            <option <?php if ($category == '106') echo 'selected="selected"'; ?> value="106">Language</option>
                                            <option <?php if ($category == '106100') echo 'selected="selected"'; ?> value="106100">African</option>
                                            <option <?php if ($category == '106101') echo 'selected="selected"'; ?> value="106101">Ancient</option>
                                            <option <?php if ($category == '106102') echo 'selected="selected"'; ?> value="106102">Asian</option>
                                            <option <?php if ($category == '106103') echo 'selected="selected"'; ?> value="106103">Eastern European/Slavic</option>
                                            <option <?php if ($category == '106104') echo 'selected="selected"'; ?> value="106104">English</option>
                                            <option <?php if ($category == '106105') echo 'selected="selected"'; ?> value="106105">English Language Learners</option>
                                            <option <?php if ($category == '106106') echo 'selected="selected"'; ?> value="106106">French</option>
                                            <option <?php if ($category == '106107') echo 'selected="selected"'; ?> value="106107">German</option>
                                            <option <?php if ($category == '106108') echo 'selected="selected"'; ?> value="106108">Italian</option>
                                            <option <?php if ($category == '106109') echo 'selected="selected"'; ?> value="106109">Linguistics</option>
                                            <option <?php if ($category == '106110') echo 'selected="selected"'; ?> value="106110">Middle Eastern</option>
                                            <option <?php if ($category == '106111') echo 'selected="selected"'; ?> value="106111">Spanish &amp; Portuguese</option>
                                            <option <?php if ($category == '106112') echo 'selected="selected"'; ?> value="106112">Speech Pathology</option>
                                        </optgroup>
                                        <optgroup label="Literature">
                                            <option <?php if ($category == '1071') echo 'selected="selected"'; ?> value="107">Literature</option>
                                            <option <?php if ($category == '107100') echo 'selected="selected"'; ?> value="107100">Anthologies</option>
                                            <option <?php if ($category == '107101') echo 'selected="selected"'; ?> value="107101">Biography</option>
                                            <option <?php if ($category == '107102') echo 'selected="selected"'; ?> value="107102">Classics</option>
                                            <option <?php if ($category == '107103') echo 'selected="selected"'; ?> value="107103">Criticism</option>
                                            <option <?php if ($category == '107104') echo 'selected="selected"'; ?> value="107104">Fiction</option>
                                            <option <?php if ($category == '107105') echo 'selected="selected"'; ?> value="107105">Poetry</option>
                                        </optgroup>
                                        <optgroup label="Mathematics">
                                            <option <?php if ($category == '108') echo 'selected="selected"'; ?> value="108">Mathematics</option>
                                            <option <?php if ($category == '108100') echo 'selected="selected"'; ?> value="108100">Advanced Mathematics</option>
                                            <option <?php if ($category == '108101') echo 'selected="selected"'; ?> value="108101">Algebra</option>
                                            <option <?php if ($category == '108102') echo 'selected="selected"'; ?> value="108102">Arithmetic</option>
                                            <option <?php if ($category == '108103') echo 'selected="selected"'; ?> value="108103">Calculus</option>
                                            <option <?php if ($category == '108104') echo 'selected="selected"'; ?> value="108104">Geometry</option>
                                            <option <?php if ($category == '108105') echo 'selected="selected"'; ?> value="108105">Statistics</option>
                                        </optgroup>
                                        <optgroup label="Science">
                                            <option <?php if ($category == '109') echo 'selected="selected"'; ?> value="109">Science</option>
                                            <option <?php if ($category == '109100') echo 'selected="selected"'; ?> value="109100">Agricultural</option>
                                            <option <?php if ($category == '109101') echo 'selected="selected"'; ?> value="109101">Astronomy</option>
                                            <option <?php if ($category == '109102') echo 'selected="selected"'; ?> value="109102">Atmospheric</option>
                                            <option <?php if ($category == '109103') echo 'selected="selected"'; ?> value="109103">Biology</option>
                                            <option <?php if ($category == '109104') echo 'selected="selected"'; ?> value="109104">Chemistry</option>
                                            <option <?php if ($category == '109105') echo 'selected="selected"'; ?> value="109105">Ecology</option>
                                            <option <?php if ($category == '109106') echo 'selected="selected"'; ?> value="109106">Geography</option>
                                            <option <?php if ($category == '109107') echo 'selected="selected"'; ?> value="109107">Geology</option>
                                            <option <?php if ($category == '109108') echo 'selected="selected"'; ?> value="109108">Physics</option>
                                        </optgroup>
                                        <optgroup label="Social Science">
                                            <option <?php if ($category == '110') echo 'selected="selected"'; ?> value="110">Social Science</option>
                                            <option <?php if ($category == '110100') echo 'selected="selected"'; ?> value="110100">Law</option>
                                            <option <?php if ($category == '110101') echo 'selected="selected"'; ?> value="110101">Political Science</option>
                                            <option <?php if ($category == '110102') echo 'selected="selected"'; ?> value="110102">Public Administration</option>
                                            <option <?php if ($category == '110103') echo 'selected="selected"'; ?> value="110103">Psychology</option>
                                            <option <?php if ($category == '110104') echo 'selected="selected"'; ?> value="110104">Social Welfare</option>
                                            <option <?php if ($category == '110105') echo 'selected="selected"'; ?> value="110105">Sociology</option>
                                        </optgroup>
                                        <optgroup label="Society">
                                            <option <?php if ($category == '111') echo 'selected="selected"'; ?> value="111">Society</option>
                                            <option <?php if ($category == '111100') echo 'selected="selected"'; ?> value="111100">African-American Studies</option>
                                            <option <?php if ($category == '111101') echo 'selected="selected"'; ?> value="111101">Asian Studies</option>
                                            <option <?php if ($category == '111102') echo 'selected="selected"'; ?> value="111102">European &amp; Russian Studies</option>
                                            <option <?php if ($category == '111103') echo 'selected="selected"'; ?> value="111103">Indigenous Studies</option>
                                            <option <?php if ($category == '111104') echo 'selected="selected"'; ?> value="111104">Latin &amp; Caribbean Studies</option>
                                            <option <?php if ($category == '111105') echo 'selected="selected"'; ?> value="111105">Middle Eastern Studies</option>
                                            <option <?php if ($category == '111106') echo 'selected="selected"'; ?> value="111106">Women's Studies</option>
                                        </optgroup>
                                        <optgroup label="Teaching &amp; Education">
                                            <option <?php if ($category == '112') echo 'selected="selected"'; ?> value="112">Teaching &amp; Education</option>
                                            <option <?php if ($category == '112100') echo 'selected="selected"'; ?> value="112100">Curriculum &amp; Teaching</option>
                                            <option <?php if ($category == '112101') echo 'selected="selected"'; ?> value="112101">Educational Leadership</option>
                                            <option <?php if ($category == '112102') echo 'selected="selected"'; ?> value="112102">Family &amp; Childcare</option>
                                            <option <?php if ($category == '112103') echo 'selected="selected"'; ?> value="112103">Learning Resources</option>
                                            <option <?php if ($category == '112104') echo 'selected="selected"'; ?> value="112104">Psychology &amp; Research</option>
                                            <option <?php if ($category == '112105') echo 'selected="selected"'; ?> value="112105">Special Education</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </li>
                            <li>
                                <label for="itunes_block" class="element">Block from iTunes <span class="helper">Set to 'yes' if you would like to block this element from iTunes</span></label>
                                <div class="element">
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[1][element]" type="hidden" value="block"/>
                                    <select id="itunes_block" name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[1][value]">
                                        <?php
                                        if (getFieldValue($context, 'itunes', 'block') == "yes") {
                                            echo '<option value="">No</option><option value="yes" selected="selected">Yes</option>';
                                        } else {
                                            echo '<option value="">No</option><option value="yes">Yes</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </li>
                            <li>
                                <label for="itunes_duration" class="element">Duration (HH:MM:SS)<span class="helper"><a href="#" onclick="mediaDetails.updateDuration();return false;">Find this</a></span></label>
                                <div class="element">
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[2][element]" type="hidden" value="duration"/>
                                    <input id="itunes_duration" name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[2][value]" type="text" value="<?php echo getFieldValue($context, 'itunes', 'duration'); ?>"/>
                                </div>
                            </li>
                            <li style="display:none;">
                                <label for="itunes_explicit" class="element">Explicit</label>
                                <div class="element">
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[3][element]" type="hidden" value="explicit"/>
                                    <input id="itunes_explicit" name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[3][value]" type="text" value="<?php echo getFieldValue($context, 'itunes', 'explicit'); ?>"/>
                                </div>
                            </li>

                            <li>
                                <label for="itunes_subtitle" class="element">Subtitle<span class="helper">The contents of this tag are shown in the Description column in iTunes. The subtitle displays best if it is only a few words long.</span></label>
                                <div class="element">
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[5][element]" type="hidden" value="subtitle"/>
                                    <input id="itunes_subtitle" name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[5][value]" type="text" value="<?php echo getFieldValue($context, 'itunes', 'subtitle'); ?>"/>
                                </div>
                            </li>
                            <li>
                                <label for="itunes_summary" class="element">Summary<span class="helper">The contents of this tag are shown in a separate window that appears when the "circled i" in the Description column is clicked.</span></label>
                                <div class="element">
                                    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[6][element]" type="hidden" value="summary"/>
                                    <input id="itunes_summary" name="UNL_MediaHub_Feed_Media_NamespacedElements_itunes[6][value]" type="text" value="<?php echo getFieldValue($context, 'itunes', 'summary'); ?>"/>
                                </div>
                            </li>
                        </ol>
                    </fieldset>
                    <input type="submit" name="submit" id="continue3" value="Save" class="wdn-pull-left" />
                    <a href="javascript:WDN.jQuery('#deleteForm').submit();" class="wdn-button wdn-button-brand">Delete</a>
                </div>
                
                <?php
                function getFieldValue($savant, $xmlns, $element)
                {
                    if (isset($savant->media)) {
                        $class = 'UNL_MediaHub_Feed_Media_NamespacedElements_'.$xmlns;
                        if ($element = call_user_func($class .'::mediaHasElement', $savant->media->id, $element, $xmlns)) {
                            return htmlentities($element->value, ENT_QUOTES);
                        }
                    }
                    return '';
                }
                function getFieldAttributes($savant, $xmlns, $element)
                {
                    if (isset($savant->media)) {
                        $class = 'UNL_MediaHub_Feed_Media_NamespacedElements_'.$xmlns;
                        if ($element = call_user_func($class .'::mediaHasElement', $savant->media->id, $element, $xmlns)) {
                            return htmlentities(serialize($element->attributes), ENT_QUOTES);
                        }
                    }
                    return '';
                }
                ?>
            </div>
        </div>
    </div>
</form>
<?php echo $savvy->render($context->media, 'Media/DeleteForm.tpl.php'); ?>

<script type="text/javascript">

    WDN.jQuery('#geo_location legend').click(function() {
        var map;
        var marker = false;
        var lincoln = new google.maps.LatLng(41.5299085734404, -99.591595703125);
        var myOptions = {
            zoom: 6,
            center: lincoln,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
        
        google.maps.event.addListener(map, 'click', function(event) {
            if (marker != false) {
                marker.setMap(null);
            }
            marker = new google.maps.Marker({
                map: map,
                position: event.latLng,
                animation: google.maps.Animation.DROP
            });
            WDN.jQuery('#geo_lat').attr('value', event.latLng.lat());
            WDN.jQuery('#geo_long').attr('value', event.latLng.lng());
        });
    });

</script>

