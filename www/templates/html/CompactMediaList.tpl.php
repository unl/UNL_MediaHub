<?php
$limit = 3;
?>
<ul class="dcf-grid-full dcf-grid-thirds@sm dcf-col-gap-vw dcf-list-bare">
	<?php $i = 0; ?>
	<?php foreach ($context->items as $media): ?>
	<li>
		<a href="<?php echo $controller::getURL($media) ?>">
			<img src="<?php echo $media->getThumbnailURL() ?>" alt="<?php echo UNL_MediaHub::escape($media->title) ?>" />
		</a>
	</li>
	<?php if (++$i == $limit) break; ?> 
	<?php endforeach; ?>
</ul>
