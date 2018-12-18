<?php $counts = $context->getStats(); ?>
<div class="dcf-grid-thirds dcf-col-gap-4">
<?php if ($counts['video']): ?>
    <div class="wmh-stat">
        <span class="mh-count"><?php echo UNL_MediaHub::escape(UNL_MediaHub_Media::formatNumber($counts['video'])) ?></span>
        <span class="mh-context unl-font-sans">Videos</span>
    </div>
<?php endif; ?>
<?php if ($counts['audio']): ?>
    <div class="mh-stat">
        <span class="mh-count"><?php echo UNL_MediaHub::escape(UNL_MediaHub_Media::formatNumber($counts['audio'])) ?></span>
        <span class="mh-context unl-font-sans">Audios</span>
    </div>
<?php endif; ?>
<?php if ($counts['plays']): ?>
    <div class="mh-stat">
        <span class="mh-count"><?php echo UNL_MediaHub::escape(UNL_MediaHub_Media::formatNumber($counts['plays'], 1)) ?></span>
        <span class="mh-context unl-font-sans">Plays</span>
    </div>
<?php endif; ?>
</div>
