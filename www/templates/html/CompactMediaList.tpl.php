<?php
$limit = 3;
?>
<ul class="bp2-wdn-grid-set-thirds ">
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
