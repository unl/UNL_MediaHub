<div class="wdn_notice <?php echo $context->type ?>">
    <div class="close">
        <a href="#" title="Close this notice">Close this notice</a>
    </div>
    <div class="message">
        <p class="title"><?php echo $context->title ?></p>
        <p>
            <?php echo $context->body; ?>
        </p>
        <?php if (!empty($context->links)): ?>
            <ul>
                <?php foreach($context->links as $url=>$text): ?>
                    <li>
                        <a href="<?php echo $url ?>"><?php echo $text ?></a>
                    </li>
                <?php endforeach ?>
            </ul>
        <?php endif; ?>
    </div>
</div>