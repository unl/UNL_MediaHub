<?php
$controller->setReplacementData('breadcrumbs', '<ol> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'">MediaHub</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'manager/">Manage Media</a></li> <li><a href="' . $context->media->getURL() .'">'.UNL_MediaHub::escape($context->media->title).'</a></li> <li>Edit Captions</li></ol>');
?>

<div class="wdn-band">
    <div class="wdn-inner-wrapper wdn-inner-padding-no-top">
        <h1>Manage Captions for: <?php echo UNL_MediaHub::escape($context->media->title) ?></h1>
        <a href="<?php echo UNL_MediaHub_Controller::getURL() . 'manager/?view=addmedia&id=' . (int)$context->media->id?>" class="wdn-button">Edit Media</a>
        <a href="<?php echo $context->media->getURL()?>" class="wdn-button">View Media</a>
    </div>
</div>

<?php $transcoding_job = $context->media->getMostRecentTranscodingJob(); ?>
<?php if ($transcoding_job && $transcoding_job->isPending()): ?>
    <?php echo $savvy->render($context, 'Feed/Media/transcoding_notice.tpl.php'); ?>
<?php endif; ?>

<div class="wdn-band wdn-light-neutral-band">
    <div class="wdn-inner-wrapper">
        <h2 class="clear-top wdn-brand">Order Captions With Your Cost Object Number</h2>
        <div class="wdn-grid-set">
            <div class="bp2-wdn-col-two-thirds">
                <div class="important-notice">
                    <p>
                        We will manually caption this media for you. Some things to keep in mind when ordering captions:
                    </p>
                    <ul class="wdn-std">
                        <li>
                            <strong>Important</strong>: captions cost $1 per video minute, rounded up.  Example: A 3:15 minute video would cost $4.
                        </li>
                        <li>
                            Orders are usually completed within 24 hours.
                        </li>
                        <li>
                            Orders can not be canceled.
                        </li>
                        <li>
                            If you need to edit captions that you ordered, please upload them to amara and customize them there. Once the customized captions are published on amara.org, use this page to pull them down to mediahub.
                        </li>
                        <li>
                            You will be billed the month after the order is completed. We estimate that to be <?php echo date('F j, Y', UNL_MediaHub_RevAPI::getEstimatedBillingDate()) ?>.
                        </li>
                    </ul>
                </div>
            </div>
            <div class="bp2-wdn-col-one-third">
                <div class="mh-caption-sidebar">
                    <?php if (!$context->hasPendingOrder()): ?>
                    <form id="caption_order" method="post">
                        <?php if ($duration = $context->media->findDuration()): ?>
                            <?php $estimate = ceil($duration->getTotalSeconds()/60); ?>
                            <input type="hidden" name="media_duration" value="<?php echo UNL_MediaHub::escape($duration->getString()); ?>" />
                            <input type="hidden" name="estimate" value="<?php echo UNL_MediaHub::escape($estimate) ?>" />
                            <h3 class="clear-top">Caption your video for <strong>$<?php echo UNL_MediaHub::escape($estimate) ?>.</strong></h3>  
                        <?php else: ?>
                            <p>
                                We were unable to find the duration of the video, and can not estimate the cost.
                            </p>
                        <?php endif; ?>
                        <ul>
                            <li>
                                <label>
                                    Cost Object Number
                                    <input type="text" name="cost_object" required />
                                </label>
                            </li>
                        </ul>
                        <input type="hidden" name="__unlmy_posttarget" value="order_rev" />
                        <input type="hidden" name="media_id" value="<?php echo (int)$context->media->id ?>" />
                        <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
                        <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">
                        <input type="submit" id="caption_submit_button" value="Order captions">
                        <p class="wdn-icon wdn-icon-attention wdn-sans-serif">Orders can not be canceled.</p>
                    </form>
                    <?php else: ?>
                    <p>Great news! There is an order already in the works.</p>
                    <?php endif; ?>
                </div>  

            </div>
        </div>
    </div>
</div>

<div class="wdn-band">
    <div class="wdn-inner-wrapper">
    <h2 class="clear-top wdn-brand">Self-Manage Captions With Amara</h2>
        <div class="wdn-grid-set">
            <div class="bp2-wdn-col-two-thirds">
                <p>
                    <a href="http://amara.org">amara.org</a> is a free service which helps you caption videos. To caption your video you will need to do the following.
                </p>
                <ol>
                    <li>Go to amara.org and create/edit captions for the video.</li>
                    <li>Follow the instructions on amara.org to publish the new captions</li>
                    <li>Come back here, and click the button to 'pull captions from amara.org'</li>
                </ol>
            </div>
            <div class="bp2-wdn-col-one-third">
                <?php $edit_captions_url = $context->getEditCaptionsURL(); ?>
                <?php if (!$edit_captions_url): ?>
                    <p>Please wait for your video to be optimized before captioning on Amara</p>
                <?php else: ?>
                    <a class="wdn-button wdn-button-brand" href="<?php echo $context->getEditCaptionsURL(); ?>">Edit Captions on amara</a><br><br>
                    <form method="post">
                        <input type="hidden" name="__unlmy_posttarget" value="pull_amara" />
                        <input type="hidden" name="media_id" value="<?php echo (int)$context->media->id ?>" />
                        <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
                        <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">
                        <input type="submit" value="Pull Captions from amara.org">
                    </form>
                <?php endif ?>
            </div>
        </div>
    </div>
</div> 

<div class="wdn-band wdn-light-neutral-band">
    <div class="wdn-inner-wrapper">
        <h2 class="wdn-brand clear-top">Order history and status</h2>
        <p>View the current status of your orders</p>
        <table class="wdn_responsive_table flush-left">
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Date of order</th>
                    <th>Requester</th>
                    <th>Status of order</th>
                    <th>Cost</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php $orders = $context->getRevOrderHistory()->items; ?>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td data-header="Order Number">
                        <?php echo (int)$order->id ?>
                    </td>
                    <td data-header="Date of order">
                        <?php echo UNL_MediaHub::escape($order->datecreated) ?>
                    </td>
                    <td data-header="Requester">
                        <?php echo UNL_MediaHub::escape($order->uid) ?>
                    </td>
                    <td data-header="Status of order">
                        <?php echo $order->status ?>
                        <?php if (UNL_MediaHub_RevOrder::STATUS_ERROR == $order->status): ?>
                            -- <?php echo UNL_MediaHub::escape($order->error_text) ?>
                        <?php endif; ?>
                    </td>
                    <td data-header="Cost">
                        $<?php echo UNL_MediaHub::escape($order->estimate) ?>
                    </td>
                    <td data-header="Actions">
                        <a href="<?php echo $order->getDetailsURL() ?>">view details</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <h2 class="wdn-brand">Caption track history</h2>
        <p>You can manage old captions.</p>
        <table class="wdn_responsive_table flush-left">
            <thead>
            <tr>
                <th>Date of caption track</th>
                <th>Source</th>
                <th>Comments</th>
                <th>Files</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php $text_tracks = $context->getTrackHistory()->items; ?>
                <?php foreach ($text_tracks as $track): ?>
                    <tr>
                        <td data-header="Date of caption track">
                            <?php echo UNL_MediaHub::escape($track->datecreated) ?>
                        </td>
                        <td data-header="Source">
                            <?php echo UNL_MediaHub::escape($track->source) ?>
                        </td>
                        <td data-header="Comments">
                            <?php echo UNL_MediaHub::escape($track->revision_comment) ?>
                        </td>
                        <td data-header="Files">
                            <ul>
                                <?php foreach ($track->getFiles()->items as $file): ?>
                                    <li>
                                        <a href="<?php echo $file->getURL() ?>&amp;download=1" target="_blank"><?php echo UNL_MediaHub::escape($file->language) ?>.<?php echo $file->format ?></a>,
                                        <a href="<?php echo $file->getSrtURL() ?>&amp;download=1" target="_blank"><?php echo UNL_MediaHub::escape($file->language) ?>.srt</a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td data-header="Actions">
                            <?php if ($context->media->media_text_tracks_id == $track->id): ?>
                                (active)
                            <?php else: ?>
                                <form method="post">
                                    <input type="hidden" name="__unlmy_posttarget" value="set_active_text_track" />
                                    <input type="hidden" name="media_id" value="<?php echo (int)$context->media->id ?>" />
                                    <input type="hidden" name="text_track_id" value="<?php echo (int)$track->id ?>" />
                                    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
                                    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">
                                    <input type="submit" value="Set Active">
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="wdn-band">
    <div class="wdn-inner-wrapper">
        <div>
            <h2 class="wdn-brand">Get Help</h2>
            <p>
                If you have questions or comments, please use the 'Email Us' tab on this page or email <a href="mailto:mysupport@unl.edu">MySupport</a>.
            </p>
        </div>
    </div>
</div>
