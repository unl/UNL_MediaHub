<div class="dcf-bleed dcf-pt-6 dcf-mb-6">
    <div class="dcf-wrapper">
        <h2 class="dcf-txt-h2">Transcode Manager</h2>
        <div class="dcf-grid dcf-col-gap-vw dcf-row-gap-4">
            <div class="dcf-col-100% dcf-col-25%-start@md">
                <ul class="dcf-list-bare">
                    <li>
                        <form class="dcf-form" id="reset" method="POST">
                            <input type="hidden" name="command" value="<?php echo UNL_MediaHub_TranscodeManager::COMMAND_RESTART; ?>">
                            <input type="submit" class="dcf-btn dcf-btn-primary" value="Restart Transcoder" onclick="return confirm('Are you sure you want to restart the transcoder?');">
                        </form>
                    </li>
                    <li>
                        <form class="dcf-form" id="list" method="POST">
                            <input type="hidden" name="command" value="<?php echo UNL_MediaHub_TranscodeManager::COMMAND_LIST_WORKERS; ?>">
                            <input type="submit" class="dcf-btn dcf-btn-primary" value="List Workers">
                        </form>
                    </li>
                </ul>
            </div>
            <div class="dcf-col-100% dcf-col-75%-end@md">
            <?php if ($context->commandAttempted()) { ?>
                <h3 class="dcf-txt-h6">Command Results (<?php echo $context->getCommandResults()->code === 0 ? 'Success' : 'Error'; ?>)</h3>
                <div class="dcf-b-1 dcf-b-dotted dcf-p-4 dcf-mb-6">
                    <?php echo !empty($context->getCommandResults()->output) ? implode('<br>', $context->getCommandResults()->output) : 'No command output to display.'; ?>
                </div>
            <?php } ?>

            <h3 class="dcf-txt-h6">Current Transcoding Jobs</h3>
            <?php if ($context->hasJobs()) { ?>
                <?php var_dump($context->getJobs()); ?>
            <?php } else { ?>
                <p>There are not any jobs transcoding.</p>
            <?php } ?>
            </div>
        </div>
    </div>
</div>