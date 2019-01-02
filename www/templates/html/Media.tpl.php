<?php
$type = 'audio';
if ($context->isVideo()) {
    $type = 'video';
    $height = 529;
    $width = 940;
    $dimensions = $context->getVideoDimensions();
    if (isset($dimensions['width'])) {
        // Scale everything down to 450 wide
        $height = round(($width/$dimensions['width'])*$dimensions['height']);
    }
}

$user = UNL_MediaHub_AuthService::getInstance()->getUser();

$context->loadReference('UNL_MediaHub_Media_Comment');
$controller->setReplacementData('title', htmlspecialchars($context->title) . ' | MediaHub | University of Nebraska-Lincoln');
// TODO: disable breadcrumbs since currently not supported in 5.0 App templates
//$controller->setReplacementData('breadcrumbs', '<ol> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'">MediaHub</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'search/">All Media</a></li> <li>'.htmlspecialchars($context->title).'</li></ol>');
$meta = '
<meta property="og:type" content="'.$type.'">
<meta property="og:title" content="'.htmlentities($context->title, ENT_QUOTES).'" />
<meta property="og:description" content="'.htmlentities(strip_tags($context->description), ENT_QUOTES).'" />
<meta property="og:image" content="'.$context->getThumbnailURL().'" />
<meta property="og:url" content="'.$context->getURL().'">
';

if ($context->privacy !== 'PUBLIC') {
    $meta .= '<meta name="robots" content="noindex">';
}

if ($type == 'video') {
    $meta .= '
    <meta property="og:video:height" content="'.$height.'" />
    <meta property="og:video:width" content="'.$width.'" />
    <meta property="og:video:url" content="'.$context->getMediaURL().'" />
    <meta property="og:video:secure_url" content="'.$context->getMediaURL().'" />
    <meta property="og:video:type" content="video/mp4" />
    ';
} else {
    $meta .= '
    <meta property="og:audio" content="'.$context->url.'" />
    <meta property="og:audio:title" content="'.htmlentities($context->title, ENT_QUOTES).'" />
    ';
}
$controller->setReplacementData('head', $meta);
$getTracks = $context->getTextTrackURLs();

?>

<div class="dcf-bleed mh-video-band">
    <div class="dcf-wrapper">
        <div class="wdn-responsive-embed wdn-aspect16x9">
            <iframe height="667" src="<?php echo $controller->getURL($context)?>?format=iframe&autoplay=0&preload=auto" allowfullscreen title="watch media"></iframe>
        </div>
    </div>
</div>


<div class="dcf-bleed dcf-pt-8 dcf-pb-8">
    <div class="dcf-wrapper mh-media-page">
        <?php if ($user && $context->userCanEdit($user)): ?>
          <div class="dcf-pb-4">
            <a href="<?php echo UNL_MediaHub_Controller::getURL() . 'manager/?view=addmedia&id=' . $context->id ?>" class="dcf-btn dcf-btn-primary"><span class="wdn-icon-wrench wdn-icon"></span>Edit Media Details</a>
          </div>
        <?php endif; ?>
        <div class="dcf-grid dcf-col-gap-vw dcf-row-gap-4">
            <div class="dcf-col-100% dcf-col-75%-start@sm">
                <h1><?php echo UNL_MediaHub::escape($context->title); ?></h1>
                <?php if ($element = UNL_MediaHub_Feed_Media_NamespacedElements_itunes::mediaHasElement($context->id, 'subtitle')): ?>
                    <p class="unl-font-sans itunes_subtitle"><?php echo UNL_MediaHub::escape($element->value) ?></p>
                <?php endif; ?>
                <?php
                $purifier = new HTMLPurifier();
                $summary = strip_tags($context->description, "<a><br><p><ul><ol><li><strong><em>");
                $summary = $purifier->purify($summary);
            
                if ($element = UNL_MediaHub_Feed_Media_NamespacedElements_itunes::mediaHasElement($context->id, 'summary')) {
                    $summary .= '<div class="itunes_summary">'.$element->value.'</div>';
                }
                ?>

                <div class="mh-authoring dcf-grid-thirds@sm dcf-col-gap-vw dcf-txt-center">
                    <?php if (!empty($context->author)): // @TODO present author with more info (standardize people records) ?>
                        <div class="mh-stat">
                            <span class="mh-count"><?php echo UNL_MediaHub::escape($context->author); ?></span>
                            <span class="mh-context unl-font-sans">Author</span>
                        </div>
                    <?php endif; ?>
                    <div class="mh-stat">
                        <span class="mh-count"><?php echo date('m/d/Y', strtotime($context->datecreated)); ?></span>
                        <span class="mh-context unl-font-sans">Added</span>
                    </div>
                    <div class="mh-stat">
                        <span class="mh-count"><?php echo UNL_MediaHub::escape($context->play_count) ?></span>
                        <span class="mh-context unl-font-sans">Plays</span>
                    </div>
                </div>
                <hr>
                
                <?php if($getTracks): ?>
                    <div class="mediahub-onpage-captions">
                        <?php echo $savvy->render($context, 'MediaPlayer/Transcript.tpl.php'); ?>
                    </div>
                    <hr>
                <?php endif; ?>
               
                <h2 class="unl-font-sans">Description</h2>
                <div class="mh-summary"><?php echo $summary; ?></div>


                <?php if (($tags = $context->getTags()) || ($user && $context->userCanEdit($user))): ?>
                    <hr>
                    <ul id="mediaTags" class="unl-font-sans">
                        <li class="unl-font-sans mh-tag-label">Tags:</li>
                        <?php foreach ($tags as $tag): ?>
                            <li><a href="<?php echo UNL_MediaHub_Controller::$url.'tags/'.urlencode(trim($tag)) ?>"><?php echo UNL_MediaHub::escape($tag) ?></a></li>
                        <?php endforeach; ?>
                        <?php if ($user && $context->userCanEdit($user)): ?>
                            <li id="mediaTagsAdd">
                                <a href="#" title="Add a tag"></a>
                                <form id="addTags" method="post">
                                    <label for="new_tag">Please enter a tag name
                                       <input type="text" value="" name="tags" id="new_tag" >
                                    </label>
                                    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
                                    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">
                                    <input type="submit" value="Add" >
                                </form>
                            </li>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>
                <hr>
                <div id="comments">
                    <?php
                        $page->addScriptDeclaration("WDN.loadCSS('../templates/html/css/comments.css?v=" . UNL_MediaHub_Controller::getVersion() . "');");
                    ?>
                    <h2 class="unl-font-sans">Comments <span class="wdn-icon wdn-icon-comment" aria-hidden="true"></span></h2>
                    <span class="subhead">
                        <?php echo count($context->UNL_MediaHub_Media_Comment); ?> Comments
                        <?php if ($user): ?>
                            | <a href="#commentForm">Leave Yours</a>
                        <?php endif; ?>
                    </span>
                    <?php if (count($context->UNL_MediaHub_Media_Comment)): ?>
                        <ul>
                            <?php foreach ($context->UNL_MediaHub_Media_Comment as $comment): ?>
                                <li>
                                    <?php if ($name = UNL_Services_Peoplefinder::getFullName($comment['uid'])): ?>
                                    <?php endif; ?>

                                    <blockquote><?php echo UNL_MediaHub::escape(strip_tags($comment['comment'])); ?></blockquote>
                                    <div class="mh-user">
                                        <img alt="Your Profile Pic" src="http://planetred.unl.edu/pg/icon/unl_<?php echo UNL_MediaHub::escape($comment['uid']); ?>/small/" class="profile_pic small"> 
                                        <div class="commenter unl-font-sans sec_header clear-top"><?php echo $name; ?></div>
                                        <em><?php echo date('m/d/y g:i a', strtotime($comment['datecreated'])); ?></em>
                                    </div>
                                    <div class="clear"></div>
                                    <hr>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>

                <?php if ($user): ?>

                    <?php $form = new UNL_MediaHub_Media_Comment_Form(); ?>
                    <?php echo $savvy->render($form); ?>
                <?php else: ?>
                    <a href="https://login.unl.edu/cas/login?service=<?php echo urlencode(UNL_MediaHub_Controller::getURL($context)) ?>">Log in to post comments</a>
                <?php endif; ?>

            </div>

            <div class="dcf-col-100% dcf-col-25%-end@sm mh-sidebar">

              <div class="dcf-pt-4">
                <div>

                  <a class="dcf-btn embed mh-hide-bp2"><span class="wdn-icon-plus wdn-icon" aria-hidden="true"></span>Embed</a>
                  <br><br>
                  <a href="<?php echo htmlentities($controller->getURL($context).'/download', ENT_QUOTES); ?>" target="_blank" class="dcf-btn mh-hide-bp2"><span class="wdn-icon-up-small mh-flip-180 wdn-icon" aria-hidden="true"></span>Download</a>

                </div>

                  <?php
                  $channels = $context->getFeeds();
                  echo $savvy->render($channels, 'CompactFeedList.tpl.php');
                  ?>

              </div>

            </div>
        </div>
    </div>
</div>

<div id="sharing" class="dcf-pb-8">
    <h3>Embed</h3>
    <?php $embed = $savvy->render(UNL_MediaHub_Media_Embed::getById($context->id, UNL_MediaHub_Controller::$current_embed_version)); ?>
    <label for="embed_code">
        <strong>iframe embed code</strong> Copy the following code into your page
        <textarea cols="25" rows="6" id="embed_code" onclick="this.select(); return false;"><?php echo htmlentities($embed, ENT_COMPAT | ENT_HTML401, "UTF-8"); ?></textarea>
    </label>
    <?php if (3 == UNL_MediaHub_Controller::$current_embed_version): ?>
        <!--
        Old embed code for testing purposes
        <?php echo $savvy->render(UNL_MediaHub_Media_Embed::getById($context->id, 2)); ?>
        -->
    <?php endif; ?>
</div> 
