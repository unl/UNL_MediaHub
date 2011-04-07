<?php
if (count($context->items)) :
?>
<h5><?php echo $context->label; ?></h5>
<?php if ($parent->context instanceof UNL_MediaHub_Media): ?>
<p class="">This media is part of the following channels:</p>
<?php endif; ?>
<ul class="channels">
    <?php foreach ($context->items as $channel): ?>
    <li>
        <a href="<?php echo UNL_MediaHub_Controller::getURL($channel); ?>" title="<?php echo htmlentities($channel->description, ENT_QUOTES); ?>">
            <img src="<?php echo UNL_MediaHub_Controller::getURL($channel).'/image'; ?>" alt="<?php echo htmlentities($channel->title, ENT_QUOTES); ?>" />
            <span class="title"><?php echo $channel->title?></span>
        </a>
    </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>