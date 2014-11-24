<?php
$type = 'audio';
if ($context->isVideo()) {
    $type = 'video';
    $height = 529;
    $width = 940;
    $dimensions = $context->getVideoDimensions();
    if (isset($dimensions[0])) {
        // Scale everything down to 450 wide
        $height = round(($width/$dimensions[0])*$dimensions[1]);
    }
}

$context->loadReference('UNL_MediaHub_Media_Comment');
$controller->setReplacementData('title', htmlspecialchars($context->title) . ' | MediaHub | University of Nebraska-Lincoln');
$controller->setReplacementData('breadcrumbs', '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'">MediaHub</a></li> <li>'.htmlspecialchars($context->title).'</li></ul>');
$meta = '
<meta name="title" content="'.htmlentities($context->title, ENT_QUOTES).'" />
<meta name="description" content="'.htmlentities(strip_tags($context->description), ENT_QUOTES).'" />
<link rel="image_src" href="'.$context->getThumbnailURL().'" />
<script type="text/javascript">
    WDN.initializePlugin("modal", [function() {
        WDN.jQuery(\'span.embed\').colorbox({inline: true, href:\'#sharing\', width:\'600px\', height:\'310px\'});
    }]);
</script>
<meta name="medium" content="'.$type.'" />
<meta property="og:type" content="'.$type.'">
';

if ($context->privacy !== 'PUBLIC') {
    $meta .= '<meta name="robots" content="noindex">';
}

if ($type == 'video') {
    $meta .= '
    <link rel="video_src" href="'.$context->url.'" />
    <meta property="og:video" content="'.htmlentities($context->url, ENT_QUOTES).'" />
    <meta property="og:video:height" content="'.$height.'" />
    <meta property="og:video:width" content="'.$width.'" />
    <meta property="og:video:type" content="'.$context->type.'" />
    <meta property="og:image" content="'.$context->getThumbnailURL().'">
    <meta property="og:video" content="'.UNL_MediaHub_Controller::getURL($context).'" />
    <meta property="og:video:type" content="text/html" />
    ';
} else {
	$meta .= '
	<meta property="og:audio" content="'.$context->url.'" />
	<meta property="og:audio:title" content="'.htmlentities($context->title, ENT_QUOTES).'" />
	<meta property="og:audio:type" content="'.$context->type.'" />
	';
}
$controller->setReplacementData('head', $meta);

// Store the mediaplayer code in a variable, so we can re-use it for the embed
$mediaplayer = $savvy->render($context, 'MediaPlayer.tpl.php');
?>


<div class="wdn-band">
    <div class="wdn-inner-wrapper wdn-inner-padding-no-bottom">
    <?php echo $mediaplayer; ?>
    </div>

</div>

<div class="wdn-band">
    <div class="wdn-inner-wrapper"> 
        <div class="wdn-grid-set">
            <div class="bp2-wdn-col-one-fourth mh-sidebar wdn-pull-right">
                
            <?php if ($user && $context->userCanEdit($user)): ?>
                <div>
                    <a href="<?php echo UNL_MediaHub_Controller::getURL() . 'manager/?view=addmedia&id=' . $context->id ?>" class="wdn-button wdn-button-brand"><span class="wdn-icon-rocket wdn-icon"></span>Edit Page</a>
                </div>
            <?php endif; ?>

            </div>
            <div class="bp2-wdn-col-three-fourths">
                <h1 class="wdn-brand clear-top"><?php echo $context->title; ?></h1>
                <?php if ($element = UNL_MediaHub_Feed_Media_NamespacedElements_itunes::mediaHasElement($context->id, 'subtitle')): ?>
                    <h3 class="itunes_subtitle"><?php echo $element->value ?></h3>
                <?php endif; ?>
                <?php $summary = $context->description;
                
                 $summary = strip_tags($summary, "<a><br><p><ul><ol><li><strong><em>");
                
                if ($element = UNL_MediaHub_Feed_Media_NamespacedElements_itunes::mediaHasElement($context->id, 'summary')):
                    $summary .= '<span class="itunes_summary">'.$element->value.'</span>';
                endif;
                ?>

                <p>
                    <?php if (!empty($context->author)): // @TODO present author with more info (standardize people records) ?>
                        <div class="wdn-sans-serif">Author: <?php echo $context->author; ?></div>
                    <?php endif; ?>

                    <div class="wdn-sans-serif">Added: <?php echo date('m/d/Y', strtotime($context->datecreated)); ?></div>
                </p>

                <div class="wdn-grid-set wdn-center">

                    <div class="wdn-col-one-seventh mh-stat">
                        <span class="mh-count wdn-brand"><?php echo $context->play_count ?></span>
                        <span class="mh-context wdn-sans-serif">Plays</span>
                    </div> 

                    <div class="wdn-col-one-seventh mh-stat">
                        <span class="mh-count wdn-brand"><?php echo count($context->UNL_MediaHub_Media_Comment); ?></span>
                        <span class="mh-context wdn-sans-serif">Comments</span>
                    </div> 

                    <div class="wdn-col-one-third mh-stat">
                        <?php if($type == "video"): ?>
                            <span class="mh-ratio wdn-brand"><?php echo $dimensions[0] . 'x' .$dimensions[1];?></span>
                            <span class="mh-size wdn-brand">
                                <?php 
                                if(!empty($context->length)) {
                                    $s = array('bytes', 'kb', 'MB', 'GB');
                                    $e = floor(log($context->length)/log(1024));
                                    echo sprintf('%.2f '.$s[$e], ($context->length/pow(1024, floor($e))));
                                }
                                ?>
                            </span>
                        <?php else: ?>
                            <span class="mh-size wdn-brand">
                                <?php 
                                if(!empty($context->length)) {
                                    $s = array('bytes', 'kb', 'MB', 'GB');
                                    $e = floor(log($context->length)/log(1024));
                                    echo sprintf('%.2f '.$s[$e], ($context->length/pow(1024, floor($e))));
                                }
                                ?>
                            </span>
                            <span class="mh-ratio wdn-brand"></span>
                        <?php endif; ?>
                        
                    </div>  
                </div>          

                <p><?php echo $summary; ?></p>


                <hr>
                <ul id="mediaTags" class="wdn-sans-serif">
                    <li class="wdn-sans-serif mh-tag-label">Tags:</li>

                    <?php foreach ($context->getTags() as $tag): ?>
                        <li><a href="<?php echo UNL_MediaHub_Controller::$url.'tags/'.urlencode(trim($tag)) ?>"><?php echo $tag ?></a></li>
                    <?php endforeach; ?>

                    <?php if (UNL_MediaHub_Controller::isLoggedIn()): ?>
                        <li id="mediaTagsAdd"><a href="#"></a><form id="addTags" method="post"><input type="text" value="" name="tags" ><input type="submit" value="Add" ></form></li>
                    <?php else: ?>
                        <li id="mediaTagsAdd"><a href="https://login.unl.edu/cas/login?service=<?php echo urlencode(UNL_MediaHub_Controller::getURL($context)) ?>">Log in to add tags </a></li>
                    <?php endif; ?>

                </ul>
                <hr>
                <div id="comments">
                    <script type="text/javascript">
                        WDN.loadCSS('../templates/html/css/comments.css');
                    </script>
                    <h6 class="wdn-sans-serif">COMMENTS <span class="wdn-icon wdn-icon-comment"></span></h6>
                    <span class="subhead"><?php echo count($context->UNL_MediaHub_Media_Comment); ?> Comments | <a href="#commentForm">Leave Yours</a></span>
                    <?php if (count($context->UNL_MediaHub_Media_Comment)): ?>
                        <ul>
                            <?php foreach ($context->UNL_MediaHub_Media_Comment as $comment): ?>
                                <li>
                                    <?php if ($name = UNL_Services_Peoplefinder::getFullName($comment['uid'])): ?>
                                    <?php endif; ?>

                                    <blockquote><?php echo htmlentities(strip_tags($comment['comment']), ENT_QUOTES); ?></blockquote>
                                    <div class="mh-user">
                                        <img alt="Your Profile Pic" src="http://planetred.unl.edu/pg/icon/unl_<?php echo $comment['uid']; ?>/small/" class="profile_pic small"> 
                                        <div class="commenter wdn-sans-serif sec_header clear-top"><?php echo $name; ?></div>
                                        <em><?php echo date('m/d/y g:i a', strtotime($comment['datecreated'])); ?></em>
                                    </div>
                                    <div class="clear"></div>
                                    <hr>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>

                <?php if (UNL_MediaHub_Controller::isLoggedIn()): ?>

                    <?php $form = new UNL_MediaHub_Media_Comment_Form(); ?>
                    <?php echo $savvy->render($form); ?>
                <?php else: ?>
                    <a href="https://login.unl.edu/cas/login?service=<?php echo urlencode(UNL_MediaHub_Controller::getURL($context)) ?>">Log in to post comments</a>
                <?php endif; ?>

            </div>
            <div class="bp2-wdn-col-one-fourth mh-sidebar">
                <div>

                    <a class="wdn-button wdn-button-brand embed mh-hide-bp2"><span class="wdn-icon-rocket wdn-icon"></span>Embed</a>
                    <br>
                    <a href="<?php echo htmlentities($context->url, ENT_QUOTES); ?>" target="_blank" class="wdn-button wdn-button-brand mh-hide-bp2"><span class="wdn-icon-rocket wdn-icon"></span>Download</a>

                </div>

                <?php
                $channels = $context->getFeeds();
                echo $savvy->render($channels, 'CompactFeedList.tpl.php');
                ?>

            </div>
        </div>
    </div>
</div>



<div id="sharing">
    <h3>Embed</h3>
    <p>Copy the following code into your unl.edu page</p>

    <?php 
    $embed = $savvy->render(UNL_MediaHub_Media_Embed::getById($context->id, UNL_MediaHub_Controller::$current_embed_version));
    ?>
    <textarea cols="25" rows="6" onclick="this.select(); return false;"><?php echo htmlentities($embed, ENT_COMPAT | ENT_HTML401, "UTF-8"); ?></textarea>
</div> 
