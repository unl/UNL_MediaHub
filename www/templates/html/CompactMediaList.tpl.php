<?php
$limit = 3;
?>
<ul class="dcf-grid-full dcf-grid-thirds@md dcf-col-gap-vw dcf-list-bare">
	<?php $i = 0; ?>
	<?php foreach ($context->items as $media): ?>
	<li>
		<a href="<?php echo $controller::getURL($media) ?>">
			<div class="dcf-ratio dcf-ratio-16x9 mh-thumbnail-clip">
				<img
					class="dcf-ratio-child dcf-obj-fit-cover"
					src="<?php echo $media->getThumbnailURL() ?>"
					alt="<?php echo UNL_MediaHub::escape($media->title) ?>"
				>
			</div>
		</a>
	</li>
	<?php if (++$i == $limit) break; ?>
	<?php endforeach; ?>
</ul>
