<div class="dcf-bleed dcf-pt-6 dcf-mb-6">
    <div class="dcf-wrapper">
        <h2 class="dcf-txt-h2">Transcode Manager</h2>
        <div class="dcf-grid dcf-col-gap-vw dcf-row-gap-4">
            <div class="dcf-col-100% dcf-col-25%-start@md">
                <ul class="dcf-list-bare">
                    <li>
                        <form class="dcf-form" id="reset" method="POST" action="<?php echo UNL_MediaHub_Controller::getURL() . 'transcode-manager/command'; ?>">
                            <input type="hidden" name="command" value="<?php echo UNL_MediaHub_TranscodeManager::COMMAND_RESTART; ?>">
                            <input type="submit" class="dcf-btn dcf-btn-primary" value="Restart Transcoder" onclick="return confirm('Are you sure you want to restart the transcoder?');">
                        </form>
                    </li>
                    <li>
                        <form class="dcf-form" id="list" method="POST" action="<?php echo UNL_MediaHub_Controller::getURL() . 'transcode-manager/command'; ?>">
                            <input type="hidden" name="command" value="<?php echo UNL_MediaHub_TranscodeManager::COMMAND_LIST_WORKERS; ?>">
                            <input type="submit" class="dcf-btn dcf-btn-primary" value="List Workers">
                        </form>
                    </li>
                </ul>
            </div>
            <div class="dcf-col-100% dcf-col-75%-end@md">
            <?php $commandResults = $context->getCommandResults(); ?>
            <?php if (!empty($commandResults)) { ?>
                <h3 class="dcf-txt-h6">Command Results (<?php echo $commandResults->code === 0 ? 'Success' : 'Error'; ?>)</h3>
                <div class="dcf-b-1 dcf-b-dotted dcf-p-4 dcf-mb-6 dcf-txt-3xs">
                    <?php echo !empty($commandResults->output) ? implode('<br>', $commandResults->output) : 'No command output to display.'; ?>
                </div>
            <?php } ?>
            <?php $context->clearCommandResults(); ?>

                <h3 class="dcf-txt-h6" id="table-description">Last 50 Transcoding Jobs <span class="dcf-subhead">Sorted by Date</span></h3>
            <?php if ($context->hasJobs()) { ?>
                <table class="dcf-table dcf-table-bordered dcf-table-responsive" aria-describedby="table-description">
                    <thead>
                        <th scope="col">ID</th>
                        <th scope="col">Type</th>
                        <th scope="col">Media ID</th>
                        <th scope="col">User</th>
                        <th scope="col">Date</th>
                        <th scope="col">Status</th>
                    </thead>
                    <tbody>
                    <?php foreach($context->getJobs() as $job): ?>
                        <tr>
                            <td class="dcf-txt-right" data-label="ID"><?php echo $job->job_id; ?></td>
                            <td class="dcf-txt-right" data-label="Type"><?php echo $job->job_type; ?></td>
                            <td class="dcf-txt-right" data-label="Media ID"><?php echo $job->media_id; ?></td>
                            <td data-label="User"><?php echo $job->uid; ?></td>
                            <td class="dcf-txt-right" data-label="Date"><?php echo $job->dateupdated; ?></td>
                            <td class="dcf-txt-right" data-label="Status"><?php
                                $status = ucfirst(strtolower($job->status));
                                if (!empty($job->error_text)) {
                                    echo '<span title="' . $job->error_text . '">' . $status . '</span>';
                                } else {
	                                echo $status;
                                }
                            ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p>There are not any jobs transcoding.</p>
            <?php } ?>
            </div>
        </div>
    </div>
</div>
