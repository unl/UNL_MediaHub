<?php
$limit = 3;
?>
<ul class="dcf-d-grid dcf-grid-cols-1 dcf-grid-cols-3@md dcf-col-gap-vw" role="list">
	<?php $i = 0; ?>
	<?php foreach ($context->items as $media): ?>
	<li>
		<a href="<?php echo $controller::getURL($media) ?>">
			<div class="mh-thumbnail-clip">
				<img
					class="dcf-16x9 dcf-obj-fit-cover"
					src="<?php echo $media->getThumbnailURL() ?>"
					alt="<?php echo UNL_MediaHub::escape($media->title) ?>"
				>
			</div>
		</a>
	</li>
	<?php if (++$i == $limit) break; ?>
	<?php endforeach; ?>
</ul>
