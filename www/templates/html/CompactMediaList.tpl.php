<?php
$limit = 3;
?>
<ul class="dcf-grid">
	<?php $i = 0; ?>
	<?php foreach ($context->items as $media): ?>
	<li class="dcf-col-100% dcf-col-33%@sm">
		<a href="<?php echo $controller::getURL($media) ?>">
			<img src="<?php echo $media->getThumbnailURL() ?>" alt="<?php echo UNL_MediaHub::escape($media->title) ?>" />
		</a>
	</li>
	<?php if (++$i == $limit) break; ?> 
	<?php endforeach; ?>
</ul>
