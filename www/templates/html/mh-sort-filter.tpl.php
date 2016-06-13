<nav class="mh-sort-filter" aria-labelledby="mh-sort-filter-<?php echo $context->group_id; ?>">
    <span id="mh-sort-filter-<?php echo $context->group_id; ?>" class="sort-filter-title"><?php echo $context->label; ?></span>
    <ul class="mh-btn-group">

        <?php foreach ($context->buttons as $key=>$details):?>
            <?php
            $is_current_option = ($context->selected_key == $key) ? true : false;
            $active_class = $is_current_option ? 'active' : '';
            ?>
            <li>
                <a href="<?php echo htmlentities($details['url'], ENT_QUOTES) ?>" class="wdn-button wdn-button-brand <?php echo $active_class ?>">
                    <?php if ($is_current_option): ?>
                        <span aria-hidden="true" class="mh-sort-filter-active"></span>
                    <?php endif; ?>
                    <?php echo $details['label'] ?>
                    <?php if ($is_current_option): ?>
                        <span class="wdn-text-hidden">(currently selected)</span>
                    <?php endif; ?>
                </a>
            </li>
        <?php endforeach ?>
    </ul>
</nav>
