<?php
$type = 'audio';
$height = 529;
$width = 940;
$context->loadReference('UNL_MediaYak_Media_Comment');
if (UNL_MediaYak_Media::isVideo($context->type)) {
    $type = 'video';
    $dimensions = UNL_MediaYak_Media::getMediaDimensions($context->id);
    if (isset($dimensions['width'])) {
        // Scale everything down to 450 wide
        $height = round(($width/$dimensions['width'])*$dimensions['height']);
    }
}
UNL_MediaYak_Controller::setReplacementData('title', 'UNL | Media Hub | '.htmlspecialchars($context->title));
UNL_MediaYak_Controller::setReplacementData('breadcrumbs', '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaYak_Controller::getURL().'">Media</a></li> <li>'.htmlspecialchars($context->title).'</li></ul>');
$meta = '
<meta name="title" content="'.htmlentities($context->title, ENT_QUOTES).'" />
<meta name="description" content="'.htmlentities($context->description, ENT_QUOTES).'" />
<link rel="image_src" href="'.UNL_MediaYak_Controller::$thumbnail_generator.urlencode($context->url).'" />
<script type="text/javascript">
function playerReady(thePlayer) {
    //start the player and JS API
    WDN.videoPlayer.createFallback.addJWListeners(document.getElementById(thePlayer.id));
}
</script>
<meta name="medium" content="'.$type.'" />';
if ($type == 'video') {
    $meta .= '<link rel="video_src" href="'.$context->url.'" />';
}
UNL_MediaYak_Controller::setReplacementData('head', $meta);
?>
<?php 
if ($type == 'video') {
?>
        <video height="<?php echo $height; ?>" width="<?php echo $width; ?>" autoplay src="<?php echo $context->url?>" controls poster="<?php echo UNL_MediaYak_Controller::$thumbnail_generator.($context->url)?>">
            <object type="application/x-shockwave-flash" style="width:<?php echo $width; ?>px;height:<?php echo $height; ?>px" data="/wdn/templates_3.0/includes/swf/player4.3.swf">
                <param name="movie" value="/wdn/templates_3.0/includes/swf/player4.3" />
                <param name="allowfullscreen" value="true" />
                <param name="allowscriptaccess" value="always" />
                <param name="wmode" value="transparent" />
                <param name="flashvars" value="file=<?php echo urlencode($context->url)?>&amp;image=<?php echo urlencode(UNL_MediaYak_Controller::$thumbnail_generator.urlencode($context->url))?>&amp;volume=100&amp;controlbar=over&amp;autostart=true&amp;skin=/wdn/templates_3.0/includes/swf/UNLVideoSkin.swf" /> 
            </object>
        </video>
<?php 
}
?>
<div class="three_col left supportingContent">
    <h2><?php echo $context->title; ?></h2>
    <?php
    if ($element = UNL_MediaYak_Feed_Media_NamespacedElements_itunes::mediaHasElement($context->id, 'subtitle')) {
        echo '<h3 class="itunes_subtitle">'.$element->value.'</h3>';
    }
    $summary = $context->description;
    if ($element = UNL_MediaYak_Feed_Media_NamespacedElements_itunes::mediaHasElement($context->id, 'summary')) {
        $summary .= '<span class="itunes_summary">'.$element->value.'</span>';
    }
    ?>
  <p><?php echo $summary; ?></p>
    <div id="comments">
    <h4>Comments</h4>
    <span class="subhead"><?php echo count($context->UNL_MediaYak_Media_Comment); ?> Comments | <a href="#commentForm">Leave Yours</a></span>
    <?php
    if (count($context->UNL_MediaYak_Media_Comment)) {
        echo '<ul>';
        foreach ($context->UNL_MediaYak_Media_Comment as $comment) {
            echo '<li>';
            if ($name = UNL_Services_Peoplefinder::getFullName($comment['uid'])) {
                
            }
            echo '<img alt="Your Profile Pic" src="http://planetred.unl.edu/pg/icon/unl_'.$comment['uid'].'/small/" class="profile_pic small"> ';
            echo '<h5 class="commenter sec_header">'.$name.'</h5>';
            echo '<em>'.date('m/d/y g:i a', strtotime($comment['datecreated'])).'</em>';
            echo '<blockquote>'.htmlentities(strip_tags($comment['comment']), ENT_QUOTES).'</blockquote>';
            echo '</li>';
        }
        echo '</ul>';
    }?>
    </div>
    <?php
    if (UNL_MediaYak_Controller::isLoggedIn()) {
        // show the form!
        $form = new UNL_MediaYak_Media_Comment_Form();
        echo $savvy->render($form);
    } else {
        echo '<a href="https://login.unl.edu/cas/login?service='.urlencode(UNL_MediaYak_Controller::getURL($context)).'">Log in to post comments</a>';
    }?>
</div>
<div class="col right supportingContent" id="properties">
    <div class="zenbox neutral">
    <h3>About this Media</h3>
    <?php 
	   if ($type == 'video') {
	?>
	<span class="size"><?php echo $dimensions['width'] . 'x' .$dimensions['height'];?></span>
	<?php } ?>
    <span class="duration"><?php echo $context->length;?></span>
    <span class="addedDate">Added: <?php echo date('m/d/Y', strtotime($context->datecreated)); ?></span>
  <?php if (!empty($context->author)) { // @TODO present author with more info (standardize people records) ?>
    <div class="author">
        <p>Author: <?php echo $context->author; ?></p>
    </div>
  <?php } ?>
    <span class="embed">Embed</span>
  </div>
    <?php
    $channels = $context->getFeeds();
    $channels->run();
    if (count($channels->items)) :
    ?>
    <h5>Channels</h5>
    <p class="">This media is part of the following channels:</p>
    <ul class="channels">
        <?php foreach ($channels->items as $channel): ?>
        <li>
            <a href="<?php echo UNL_MediaYak_Controller::getURL($channel); ?>">
                <img src="<?php echo UNL_MediaYak_Controller::getURL($channel).'/image'; ?>" alt="<?php echo $channel->title?>" />
                <span class="title"><?php echo $channel->title?></span>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
    <?php if(false) :?>
    <h5>Tags</h5>
    <ul class="tags">
        <li><a href="#">Tag 1</a></li>
        <li><a href="#">Tag 1</a></li>
        <li><a href="#">Tag 1</a></li>
    </ul>
    <?php endif;?>
    <h6 style="margin-top:1em;"><a href="<?php echo htmlentities($context->url, ENT_QUOTES); ?>" class="video-x-generic">Download this media file</a></h6>
</div>
<script type="text/javascript">
WDN.initializePlugin('videoPlayer');
</script>
<div id="sharing">
    <h3>Embed</h3>
    <p>Copy the following code into your unl.edu page</p>
    <textarea cols="25" rows="6" onclick="this.select(); return false;"><?php
        echo htmlentities(
            '<video height="'. $height.'" width="'.$width.'" src="'.$context->url.'" controls autobuffer poster="'.UNL_MediaYak_Controller::$thumbnail_generator.($context->url).'" style="background:url('.UNL_MediaYak_Controller::$thumbnail_generator.($context->url).') no-repeat;">'.
            '<object type="application/x-shockwave-flash" style="width:'.$width.'px;height:'.($height+48) .'px" data="http://www.unl.edu/ucomm/templatedependents/templatesharedcode/scripts/components/mediaplayer/player.swf">'.
                '<param name="movie" value="/wdn/templates_3.0/includes/swf/player4.3" ></param>'.
                '<param name="allowfullscreen" value="true"></param>'.
                '<param name="allowscriptaccess" value="always"></param>'.
                '<param name="wmode" value="transparent"></param>'.
                '<param name="flashvars" '. 
                    'value="file='.urlencode($context->url).'&amp;'.
                    'image='.urlencode(UNL_MediaYak_Controller::$thumbnail_generator.urlencode($context->url)).'&amp;'.
                    'volume=100&amp;'.
                    'autostart=false&amp;'.
                    'skin=/wdn/templates_3.0/includes/swf/player4.3" /> '.
            '</object>'.
            '</video>'.
            '<script type="text/javascript">WDN.initializePlugin("videoPlayer");</script>');
    ?></textarea>
</div>