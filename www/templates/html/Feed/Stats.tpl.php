<?php $counts = $context->getStats(); ?>
<div class="wdn-grid-set-thirds">
<?php if ($counts['video']): ?>
    <div class="wdn-col mh-stat">
        <span class="mh-count wdn-brand"><?php echo UNL_MediaHub::escape(UNL_MediaHub_Media::formatNumber($counts['video'])) ?></span>
        <span class="mh-context wdn-sans-serif">Videos</span>
    </div>
<?php endif; ?>
<?php if ($counts['audio']): ?>
    <div class="wdn-col mh-stat">
        <span class="mh-count wdn-brand"><?php echo UNL_MediaHub::escape(UNL_MediaHub_Media::formatNumber($counts['audio'])) ?></span>
        <span class="mh-context wdn-sans-serif">Audios</span>
    </div>
<?php endif; ?>
<?php if ($counts['plays']): ?>
    <div class="wdn-col mh-stat">
        <span class="mh-count wdn-brand"><?php echo UNL_MediaHub::escape(UNL_MediaHub_Media::formatNumber($counts['plays'], 1)) ?></span>
        <span class="mh-context wdn-sans-serif">Plays</span>
    </div>
<?php endif; ?>
</div>
