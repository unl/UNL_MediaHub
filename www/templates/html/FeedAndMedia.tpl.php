<?php
UNL_MediaYak_Controller::setReplacementData('head', '<link rel="alternate" type="application/rss+xml" title="'.$context->feed->title.'" href="?format=xml" />');
UNL_MediaYak_Controller::setReplacementData('title', 'UNL | Media Hub | '.htmlspecialchars($context->feed->title));
UNL_MediaYak_Controller::setReplacementData('breadcrumbs', '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaYak_Controller::getURL().'">Media Hub</a></li> <li>'.htmlspecialchars($context->feed->title).'</li></ul>');
$feed_url = htmlentities(UNL_MediaYak_Controller::getURL($context->feed), ENT_QUOTES);
?>
<div id="channelIntro" class="clear">
    <img src="<?php echo $feed_url; ?>/image" alt="<?php echo $context->feed->title; ?> Image" />
    <h1><?php echo $context->feed->title; ?></h1>
    <?php
    echo $savvy->render($context->feed, 'Feed/Creator.tpl.php');
    ?>
    <p><?php echo $context->feed->description; ?></p>
    <?php //<-- @todo Brett- can we dynamically create this section ? ?>
    <!-- 
    <h6 class="list_header">This channel is also available in:</h6>
    <ul>
       <li><img src="../manager/templates/css/images/iconItunes.png" alt="Available in iTunesU" /></li>
       <li><img src="../manager/templates/css/images/iconBoxee.png" alt="Available in Boxee" /></li>
    </ul>
     -->
</div>
<?php
echo $savvy->render($context->media_list);
?>