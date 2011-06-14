<?php
$type = 'audio';
if (UNL_MediaHub_Media::isVideo($context->type)) {
    $type = 'video';
    $height = 529;
    $width = 940;
    $dimensions = UNL_MediaHub_Media::getMediaDimensions($context->id);
    if (isset($dimensions['width'])) {
        // Scale everything down to 450 wide
        $height = round(($width/$dimensions['width'])*$dimensions['height']);
    }
}

$context->loadReference('UNL_MediaHub_Media_Comment');
UNL_MediaHub_Controller::setReplacementData('title', 'UNL | MediaHub | '.htmlspecialchars($context->title));
UNL_MediaHub_Controller::setReplacementData('breadcrumbs', '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'">MediaHub</a></li> <li>'.htmlspecialchars($context->title).'</li></ul>');
$meta = '
<meta name="title" content="'.htmlentities($context->title, ENT_QUOTES).'" />
<meta name="description" content="'.htmlentities($context->description, ENT_QUOTES).'" />
<link rel="image_src" href="'.UNL_MediaHub_Controller::$thumbnail_generator.urlencode($context->url).'" />
<script type="text/javascript">
WDN.jQuery(document).ready(function(){
    WDN.jQuery(\'span.embed\').colorbox({inline: true, href:\'#sharing\', width:\'600px\', height:\'310px\'});
});
</script>
<meta name="medium" content="'.$type.'" />';
if ($type == 'video') {
    $meta .= '<link rel="video_src" href="'.$context->url.'" />';
}
UNL_MediaHub_Controller::setReplacementData('head', $meta);

// Store the mediaplayer code in a variable, so we can re-use it for the embed
$mediaplayer = $savvy->render($context, 'MediaPlayer.tpl.php');
echo $mediaplayer;
?>

<div class="three_col left supportingContent">
    <h2><?php echo $context->title; ?></h2>
    <?php
    if ($element = UNL_MediaHub_Feed_Media_NamespacedElements_itunes::mediaHasElement($context->id, 'subtitle')) {
        echo '<h3 class="itunes_subtitle">'.$element->value.'</h3>';
    }
    $summary = $context->description;
    if ($element = UNL_MediaHub_Feed_Media_NamespacedElements_itunes::mediaHasElement($context->id, 'summary')) {
        $summary .= '<span class="itunes_summary">'.$element->value.'</span>';
    }
    ?>
  <p><?php echo $summary; ?></p>

  
    <ul id="mediaTags">
        <?php
        foreach ($context->getTags() as $tag) {
            echo '<li><a href="'.UNL_MediaHub_Controller::$url.'tags/'.urlencode(trim($tag)).'">'.$tag.'</a></li>';
        }
	    if (UNL_MediaHub_Controller::isLoggedIn()) {
	    	echo '<li id="mediaTagsAdd"><a href="#">Add tags</a><form id="addTags" method="post"><input type="text" value="" name="tags" /><input type="submit" value="Add" /></form></li>';
	    } else {
	    	echo '<li id="mediaTagsAdd"><a href="https://login.unl.edu/cas/login?service='.urlencode(UNL_MediaHub_Controller::getURL()).'">Login to add tags </a></li>';
	    }

	    ?>
    </ul>

    <div id="comments">
    <script type="text/javascript">
    	WDN.loadCSS('../templates/html/css/comments.css');
    </script>
    <h4>Comments</h4>
    <span class="subhead"><?php echo count($context->UNL_MediaHub_Media_Comment); ?> Comments | <a href="#commentForm">Leave Yours</a></span>
    <?php
    if (count($context->UNL_MediaHub_Media_Comment)) {
        echo '<ul>';
        foreach ($context->UNL_MediaHub_Media_Comment as $comment) {
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
    if (UNL_MediaHub_Controller::isLoggedIn()) {
        // show the form!
        $form = new UNL_MediaHub_Media_Comment_Form();
        echo $savvy->render($form);
    } else {
        echo '<a href="https://login.unl.edu/cas/login?service='.urlencode(UNL_MediaHub_Controller::getURL($context)).'">Log in to post comments</a>';
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
    <span class="duration"><?php 
        if(!empty($context->length)) {
            $s = array('bytes', 'kb', 'MB', 'GB');
            $e = floor(log($context->length)/log(1024));
            echo sprintf('%.2f '.$s[$e], ($context->length/pow(1024, floor($e))));
        }?></span>
    <span class="addedDate">Added: <?php echo date('m/d/Y', strtotime($context->datecreated)); ?></span>
  <?php if (!empty($context->author)) { // @TODO present author with more info (standardize people records) ?>
    <div class="author">
        <p>Author: <?php echo $context->author; ?></p>
    </div>
  <?php } ?>
    <span class="embed">Embed</span>
    <?php //@TODO add a check if user is logged in and if has permissions to this feed to edit. If true, add edit/delete links here. ?>
  </div>
    <?php
    $channels = $context->getFeeds();
    echo $savvy->render($channels, 'CompactFeedList.tpl.php');
    ?>
    <h6 style="margin-top:1em;"><a href="<?php echo htmlentities($context->url, ENT_QUOTES); ?>" class="video-x-generic">Download this media file</a></h6>
</div>
<div id="sharing">
    <h3>Embed</h3>
    <p>Copy the following code into your unl.edu page</p>
    <textarea cols="25" rows="6" onclick="this.select(); return false;"><?php echo htmlentities($mediaplayer); ?></textarea>
</div>
