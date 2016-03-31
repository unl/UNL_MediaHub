<?php
if ($context->output instanceof UNL_MediaHub_MediaList
    || (is_array($context->output) && (
        $context->output[0] instanceof UNL_MediaHub_MediaList
        || $context->output[0] instanceof UNL_MediaHub_Media
        || $context->output[0] instanceof UNL_MediaHub_DefaultHomepage
        || $context->output[0] instanceof UNL_MediaHub_FeedAndMedia))):
    echo '<?xml version="1.0" encoding="UTF-8" standalone="no"?>'; ?>
    <videos>
        <?php
        echo $savvy->render($context->output);
        ?>
    </videos>
<?php else: ?>
    <?php echo $savvy->render($context->output); ?>
<?php endif; ?>
