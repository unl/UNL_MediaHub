<?php 
    $is_review = $context->track->is_ai_generated() && empty($context->track->media_text_tracks_source_id);
?>

<?php if($is_review): ?>
    <div class="dcf-bleed dcf-mt-6">
        <div class="dcf-wrapper dcf-mb-4">
            <h2 class="dct-txt-h4 dcf-mb-0">Review Caption Track for: <?php echo UNL_MediaHub::escape($context->media->title) ?></h2>
        </div>
    </div>
<?php else: ?>
    <div class="dcf-bleed dcf-mt-6">
        <div class="dcf-wrapper">
            <h2 class="dct-txt-h4">Edit Caption Track for: <?php echo UNL_MediaHub::escape($context->media->title) ?></h2>
        </div>
    </div>
<?php endif; ?>


<div class="dcf-bleed unl-bg-lightest-gray dcf-mt-2 dcf-mb-6">
    <div class="dcf-wrapper">
        <p class="dcf-mb-6">
            This form allows simple edits of copied caption tracks.
        </p>
        <div class="dcf-grid dcf-col-gap-vw dcf-row-gap-6">
            <div class="dcf-sticky dcf-pt-4 dcf-top-0 dcf-col-100% dcf-col-25%-start@sm" style="height: fit-content;">
                <a class="dcf-btn dcf-btn-secondary dcf-mb-3" href="<?php echo UNL_MediaHub_Manager::getURL() . '?view=editcaptions&id=' . $context->media->id; ?>">
                    Back To Captions
                </a>
                <dl>
                    <dt>Created</dt>
                    <dd><?php echo UNL_MediaHub::escape($context->track->datecreated); ?></dd>
                    <dt>Source</dt>
                    <?php
                        $is_copy = !empty($context->track->media_text_tracks_source_id);
                        $track_source_formatted = $context->track->source === UNL_MediaHub_MediaTextTrack::SOURCE_AI_TRANSCRIPTIONIST ? 'AI Captions' : $context->track->source;
                    ?>
                    <dd>
                        <?php if($is_copy): ?>
                            Copied from <?php echo ucwords($track_source_formatted); ?>
                        <?php else: ?>
                            <?php echo ucwords($track_source_formatted); ?>
                        <?php endif; ?>
                    </dd>
                    <?php if(!empty($context->track->revision_comment)): ?>
                        <dt>Revision Comment</dt>
                        <dd><?php echo UNL_MediaHub::escape($context->track->revision_comment) ?></dd>
                    <?php endif; ?>
                </dl>
                <div>
                    <?php if($is_review): ?>
                        <input type="hidden" form="captions_edit" name="__unlmy_posttarget" value="save_review_ai_captions" />
                        <input type="hidden" form="captions_edit" name="text_track_id" value="<?php echo (int)$context->track->id ?>" />
                        <div class="dcf-input-checkbox">
                            <input
                                id="activate_after_review"
                                form="captions_edit"
                                name="activate_after_review"
                                type="checkbox"
                                value="1"
                                checked="checked"
                            >
                            <label for="activate_after_review">Active After Review</label>
                        </div>
                        <input class="dcf-btn dcf-btn-primary" form="captions_edit" type="submit" value="Save Review">
                    <?php else: ?>
                        <div class="dcf-mt-4" role="group" aria-label="Edit Caption Actions">
                            <input type="hidden" form="captions_edit" name="__unlmy_posttarget" value="update_text_track_file" />
                            <input class="dcf-btn dcf-btn-primary" form="captions_edit" name="submit" type="submit" id="save-captions-btn" value="Save">
                            <a href="<?php echo UNL_MediaHub_Controller::getURL() . 'manager/?view=editcaptions&id=' . (int)$context->media->id; ?>" class="dcf-btn dcf-btn-secondary" onclick="return confirm('Are you sure you want to cancel?');">Cancel</a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="dcf-mt-6 dcf-mb-6">
                    <?php
                        $padding = array(
                            '1x1' => '100%',
                            '3x4' => '133.33%',
                            '4x3' => '75%',
                            '9x16' => '177.77%',
                            '16x9' => '56.25%',
                        );

                        $prefix = 'Video Player: ';
                        $divStyle = 'padding-top: ' . $padding['16x9'] . ';';
                        if (!$context->media->isVideo()) {
                            $prefix = 'Audio Player: ';
                            $divStyle = 'height: 5.62em; max-width: 56.12rem;';
                            $aspect_ratio = '16x9';
                        } else {
                            $aspect_ratio_raw = $context->media->getAspectRatio();
                            $aspect_ratio = '16x9';
                            switch ($aspect_ratio_raw) {
                                case UNL_MediaHub_Media::ASPECT_1x1:
                                $aspect_ratio = '1x1';
                                break;
                                case UNL_MediaHub_Media::ASPECT_3x4:
                                $aspect_ratio = '3x4';
                                break;
                                case UNL_MediaHub_Media::ASPECT_4x3:
                                $aspect_ratio = '4x3';
                                break;
                                case UNL_MediaHub_Media::ASPECT_9x16:
                                $aspect_ratio = '9x16';
                                break;
                                default:
                                $aspect_ratio = '16x9';
                            }
                            $divStyle = 'padding-top: ' . $padding[$aspect_ratio] . ';';
                        }
                    ?>
                    <div style="<?php echo $divStyle; ?> overflow: hidden; position:relative; -webkit-box-flex: 1; flex-grow: 1;">
                        <iframe
                            style="bottom: 0; left: 0; position: absolute; right: 0; top: 0; border: 0; height: 100%; width: 100%;"
                            src="<?php echo UNL_MediaHub_Controller::getURL($context->media); ?>?format=iframe&autoplay=0"
                            title="<?php echo $prefix; ?> <?php echo UNL_MediaHub::escape($context->media->title); ?>"
                            allowfullscreen
                        ></iframe>
                    </div>
                    <a class="dcf-btn dcf-btn-secondary dcf-mt-1" href="<?php echo UNL_MediaHub_Controller::getURL($context->media)?>" target="_blank">View Media</a>
                </div>
            </div>
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
                <form class="dcf-form" id="captions_edit" method="POST">
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
                </form>
                <?php else: ?>
                    <?php
                        $errorNotice = new StdClass();
                        $errorNotice->type = 'dcf-notice-alert';
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