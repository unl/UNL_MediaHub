<?php
$type = 'audio';
$height = 358;
$width = 700;
$context->loadReference('UNL_MediaYak_Media_Comment');
if (UNL_MediaYak_Media::isVideo($context->type)) {
    $type = 'video';
    $dimensions = UNL_MediaYak_Media::getMediaDimensions($context->id);
    if (isset($dimensions['width'])) {
        // Scale everything down to 450 wide
        $height = round(($width/$dimensions['width'])*$dimensions['height']+48);
    }
}
UNL_MediaYak_Controller::setReplacementData('title', 'UNL | Media Hub | '.htmlspecialchars($context->title));
UNL_MediaYak_Controller::setReplacementData('breadcrumbs', '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaYak_Controller::getURL().'">Media</a></li> <li>'.htmlspecialchars($context->title).'</li></ul>');
$meta = '
<meta name="title" content="'.htmlentities($context->title, ENT_QUOTES).'" />
<meta name="description" content="'.htmlentities($context->description, ENT_QUOTES).'" />
<link rel="image_src" href="'.UNL_MediaYak_Controller::$thumbnail_generator.urlencode($context->url).'" />
<meta name="medium" content="'.$type.'" />';
if ($type == 'video') {
    $meta .= '<link rel="video_src" href="'.$context->url.'" />';
}
UNL_MediaYak_Controller::setReplacementData('head', $meta);
?>
<div class="three_col left">
    <div id="preview"></div>
    <script type="text/javascript">UNL_MediaYak.writePlayer('<?php echo addslashes($context->title); ?>','<?php echo $context->url; ?>','preview', <?php echo $width; ?>, <?php echo $height; ?>);</script>
    <div id="comments">
    <h3><?php echo count($context->UNL_MediaYak_Media_Comment); ?> Comments | <a href="#commentForm">Leave Yours</a></h3>
    <?php
    if (count($context->UNL_MediaYak_Media_Comment)) {
        echo '<ul>';
        foreach ($context->UNL_MediaYak_Media_Comment as $comment) {
            echo '<li>';
            if ($name = UNL_Services_Peoplefinder::getFullName($comment['uid'])) {
                
            }
            echo '<h4>'.$name.'</h4>';
            echo '<p><em>'.date('m/d/y g:i a', strtotime($comment['datecreated'])).'</em></p>';
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
<div class="col right">
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
  <p><?php echo $summary; ?><br />
  <?php if (!empty($context->author)) { ?><span class="author">From:</span> <?php echo $context->author; ?><br />
  <?php } ?>
  <span class="addedDate">Added:</span> <?php echo date('m/d/Y', strtotime($context->datecreated)); ?>
  </p>
    <h6>Embed</h6>
    <textarea cols="25" rows="6" onclick="this.select(); return false;"><?php
        echo htmlentities(
            '<object type="application/x-shockwave-flash" style="width:'.$width.'px;height:'.($height+48) .'px" data="http://www.unl.edu/ucomm/templatedependents/templatesharedcode/scripts/components/mediaplayer/player.swf">'.
                '<param name="movie" value="http://www.unl.edu/ucomm/templatedependents/templatesharedcode/scripts/components/mediaplayer/player.swf" ></param>'.
                '<param name="allowfullscreen" value="true"></param>'.
                '<param name="allowscriptaccess" value="always"></param>'.
                '<param name="wmode" value="transparent"></param>'.
                '<param name="flashvars" '. 
                    'value="file='.urlencode($context->url).'&amp;'.
                    'image='.urlencode(UNL_MediaYak_Controller::$thumbnail_generator.urlencode($context->url)).'&amp;'.
                    'volume=100&amp;'.
                    'autostart=false&amp;'.
                    'skin=http://www.unl.edu/ucomm/templatedependents/templatesharedcode/scripts/components/mediaplayer/UNLVideoSkin.swf" /> '.
            '</object>');
    ?></textarea>
    
    <h6 style="margin-top:1em;"><a href="<?php echo htmlentities($context->url, ENT_QUOTES); ?>" class="video-x-generic">Download this media file</a></h6>
</div>