<div class="dcf-bleed dcf-mt-6">
    <div class="dcf-wrapper">
        <h2 class="dct-txt-h4">Edit Caption Track for: <?php echo UNL_MediaHub::escape($context->media->title) ?></h2>
    </div>
</div>

<div class="dcf-bleed unl-bg-lightest-gray dcf-mt-2 dcf-mb-6">
    <div class="dcf-wrapper">
        <p class="dcf-mb-6">
            This form allows simple edits of copied caption tracks.
        </p>
        <div class="dcf-grid dcf-col-gap-vw dcf-row-gap-6">
            <dl class="dcf-col-100% dcf-col-25%-start@sm">
                <dt>Created</dt>
                <dd><?php echo UNL_MediaHub::escape($context->track->datecreated); ?></dd>
                <dt>Source</dt>
                <dd><?php echo UNL_MediaHub::escape($context->track->source); ?></dd>
                <dt>Revision Comment</dt>
                <dd><?php echo UNL_MediaHub::escape($context->track->revision_comment) ?></dd>
            </dl>
            <?php
            $validTrack = TRUE;
            try {
                $vtt = new Captioning\Format\WebvttFile();
                $vtt->loadFromString(trim($context->trackFile->file_contents));
                $cues = $vtt->getCues();
            }  catch(Exception $e) {
                $validTrack = FALSE;
                $invalidTrackMessage = 'Unable to edit caption track due to invalid format. ' . $e->getMessage();
            }
            ?>
            <div class="dcf-col-100% dcf-col-75%-end@sm">
                <?php if ($validTrack === TRUE) : ?>
                <form class="dcf-form" method="POST">
                    <input type="hidden" name="__unlmy_posttarget" value="update_text_track_file" />
                    <input type="hidden" name="media_id" value="<?php echo (int)$context->media->id ?>" />
                    <input type="hidden" name="track_id" value="<?php echo (int)$context->track->id ?>" />
                    <input type="hidden" name="track_file_id" value="<?php echo (int)$context->trackFile->id ?>" />
                    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
                    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">

                    <?php  foreach ($cues as $cueIndex => $cue): ?>
                    <?php
                        $cueName = 'cue' . $cueIndex;
                        $cueLabel = 'Track ' . $cue->getStart() . ' - ' . $cue->getStop();
                    ?>
                    <div class="dcf-form-group">
                        <label for="<?php echo $cueName; ?>"><?php echo $cueLabel; ?></label>
                        <textarea class="dcf-txt-sm" id="<?php echo $cueName; ?>" name="<?php echo $cueName; ?>" cols="60" rows="2"><?php echo trim($cue->getText());?></textarea>
                    </div>
                    <?php endforeach; ?>
                    <p id="status"></p>
                    <ol></ol>
                    <pre></pre>
                    <div class="dcf-mt-4" role="group" aria-label="Edit Caption Actions">
                        <input class="dcf-btn dcf-btn-primary" name="submit" type="submit" id="save-captions-btn" value="Save">
                        <a href="<?php echo UNL_MediaHub_Controller::getURL() . 'manager/?view=editcaptions&id=' . (int)$context->media->id?>" class="dcf-btn dcf-btn-secondary" onclick="return confirm('Are you sure you want to cancel?');">Cancel</a>
                    </div>
                </form>
                <?php else: ?>
                    <?php
                        $errorNotice = new StdClass();
                        $errorNotice->type = 'alert';
                        $errorNotice->title = 'Invalid Caption Format';
                        $errorNotice->body = $invalidTrackMessage;
                        echo $savvy->render($errorNotice, 'Notice.tpl.php');
                    ?>
                    <div class="dcf-b-solid dcf-b-1 dcf-p-2"><pre><?php echo trim($context->trackFile->file_contents); ?></pre></div>
                    <a href="<?php echo UNL_MediaHub_Controller::getURL() . 'manager/?view=editcaptions&id=' . (int)$context->media->id?>" class="dcf-mt-4 dcf-btn dcf-btn-secondary">Back to captions</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>