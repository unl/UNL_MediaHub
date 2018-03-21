<?php
$limit = 3;
?>
<ul class="wdn-grid-set-thirds ">
	<?php $i = 0; ?>
	<?php foreach ($context->items as $media): ?>
	<li class="wdn-col">
		<a href="<?php echo $controller::getURL($media) ?>">
			<img src="<?php echo $media->getThumbnailURL() ?>" alt="<?php echo UNL_MediaHub::escape($media->title) ?>" />
		</a>
	</li>
	<?php if (++$i == $limit) break; ?> 
	<?php endforeach; ?>
</ul>
