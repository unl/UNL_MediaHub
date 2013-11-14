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
UNL_MediaHub_Controller::setReplacementData('title', htmlspecialchars($context->title) . ' | MediaHub | University of Nebraska-Lincoln');
UNL_MediaHub_Controller::setReplacementData('breadcrumbs', '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'">MediaHub</a></li> <li>'.htmlspecialchars($context->title).'</li></ul>');
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
UNL_MediaHub_Controller::setReplacementData('head', $meta);
UNL_MediaHub_Controller::setReplacementData('pagetitle', '<h1>'.$context->title.'</h1>');

// Store the mediaplayer code in a variable, so we can re-use it for the embed
$mediaplayer = $savvy->render($context, 'MediaPlayer.tpl.php');
echo $mediaplayer;
?>

<div class="grid8 first supportingContent">
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
<div class="grid4 supportingContent" id="properties">
    <div class="zenbox neutral">
    <h3>About this Media</h3>
    <?php 
	   if ($type == 'video') {
	?>
	<span class="size"><?php echo $dimensions[0] . 'x' .$dimensions[1];?></span>
	<?php } ?>
    <span class="duration"><?php 
        if(!empty($context->length)) {
            $s = array('bytes', 'kb', 'MB', 'GB');
            $e = floor(log($context->length)/log(1024));
            echo sprintf('%.2f '.$s[$e], ($context->length/pow(1024, floor($e))));
        }?></span>
    <span class="addedDate">Added: <?php echo date('m/d/Y', strtotime($context->datecreated)); ?></span>
    <div class="play_count">
        Played <?php echo $context->play_count ?> times
    </div>
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
    
    <?php 
    $embed = $savvy->render(UNL_MediaHub_Media_Embed::getById($context->id, UNL_MediaHub_Controller::$current_embed_version));
    ?>
    <textarea cols="25" rows="6" onclick="this.select(); return false;"><?php echo htmlentities($embed); ?></textarea>
</div>
