<div class="dcf-mt-2 dcf-notice <?php echo $context->type ?>" hidden>
    <h2><?php echo $context->title ?></h2>
    <div>
        <p class="dcf-mt-4"><?php echo $context->body; ?></p>
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
