<?php
if (count($context->items)) :
?>
<h5>Channels</h5>
<?php if ($parent->context instanceof UNL_MediaYak_Media): ?>
<p class="">This media is part of the following channels:</p>
<?php endif; ?>
<ul class="channels">
    <?php foreach ($context->items as $channel): ?>
    <li>
        <a href="<?php echo UNL_MediaYak_Controller::getURL($channel); ?>">
            <img src="<?php echo UNL_MediaYak_Controller::getURL($channel).'/image'; ?>" alt="<?php echo $channel->title?>" />
            <span class="title"><?php echo $channel->title?></span>
        </a>
    </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>