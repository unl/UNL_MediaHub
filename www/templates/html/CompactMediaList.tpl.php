<?php
$limit = 6;
$counts = array(
    'plays' => 0,
    'video' => 0,
    'audio' => 0,
);
/* @var $media UNL_MediaHub_Media */
foreach ($context->items as $media) {
    if ($media->isVideo()) {
        $counts['video']++;
    } else {
        $counts['audio']++;
    }
    
    $counts['plays'] += $media->play_count;
}
?>
<div class="wdn-grid-set">
    <div class="bp2-wdn-col-two-thirds mh-media-samples">
        <ul class="wdn-grid-set-fourths bp2-wdn-grid-set-sixths ">
        	<?php $i = 0; ?>
        	<?php foreach ($context->items as $media): ?>
        	<li class="wdn-col">
        		<a href="<?php echo $controller::getURL($media) ?>">
        			<img src="<?php echo $media->getThumbnailURL() ?>" alt="<?php echo htmlentities($media->title, ENT_QUOTES) ?>" />
        		</a>
        	</li>
        	<?php if (++$i == $limit) break; ?> 
        	<?php endforeach; ?>
        </ul>
    </div>
    <div class="bp2-wdn-col-one-third mh-feed-stats">
        <div class="wdn-grid-set-thirds">
            <div class="wdn-col mh-stat">
                <span class="mh-count wdn-brand"><?php echo UNL_MediaHub_Media::formatNumber($counts['video']) ?></span>
                <span class="mh-context wdn-sans-serif">Videos</span>
            </div>
            <div class="wdn-col mh-stat">
                <span class="mh-count wdn-brand"><?php echo UNL_MediaHub_Media::formatNumber($counts['audio']) ?></span>
                <span class="mh-context wdn-sans-serif">Audios</span>
            </div>
            <div class="wdn-col mh-stat">
                <span class="mh-count wdn-brand"><?php echo UNL_MediaHub_Media::formatNumber($counts['plays'], 1) ?></span>
                <span class="mh-context wdn-sans-serif">Plays</span>
            </div>
        </div>
    </div>
</div>
