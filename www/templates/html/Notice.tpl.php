<div class="dcf-mt-2 wdn_notice <?php echo $context->type ?>">
    <div class="close">
        <a href="#" title="Close this notice">Close this notice</a>
    </div>
    <div class="message">
        <p class="title"><?php echo $context->title ?></p>
        <p class="dcf-mt-4">
            <?php echo $context->body; ?>
        </p>
        <?php if (!empty($context->links)): ?>
            <ul class="dcf-mt-2 dcf-list-bare">
                <?php foreach($context->links as $url=>$text): ?>
                    <li>
                        <a class="dcf-btn dcf-btn-inverse-secondary" href="<?php echo $url ?>"><?php echo $text ?></a>
                    </li>
                <?php endforeach ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
