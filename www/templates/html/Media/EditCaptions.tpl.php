<div class="wdn_band">
    <div class="wdn_inner_wrapper">
        <h1>Edit Captions for: <?php echo $context->media->title ?></h1>
        <a href="<?php echo UNL_MediaHub_Controller::getURL() . 'manager/?view=addmedia&id=' . $context->media->id?>" class="wdn-button">Go back to editing the media</a>
        <a href="<?php echo $context->media->getURL()?>" class="wdn-button">View the media</a>
        <div class="wdn-grid-set">
            <div class="wdn-col-one-half">
                <h2>Order captions</h2>
                <form method="post">
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
                        
                        <h3>Estimated cost:</h3>
                        <?php if ($duration = $context->media->findDuration()): ?>
                            <?php $estimate = ceil($duration['seconds']/60); ?>
                            <input type="hidden" name="media_duration" value="<?php echo $duration['string'] ?>" />
                            <input type="hidden" name="estimate" value="<?php echo $estimate ?>" />
                            <table>
                                <thead>
                                    <tr>
                                        <th>Media duration</th>
                                        <th>Est. cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo $duration['string'] ?></td>
                                        <td>$<?php echo $estimate ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>
                                We were unable to find the duration of the video, and can not estimate the cost.
                            </p>
                        <?php endif; ?>
                    </div>
                    <ul>
                        <li>
                            <label>
                                Cost Object Number
                                <input type="text" name="cost_object" required />
                            </label>
                        </li>
                    </ul>
                    
                    <input type="hidden" name="__unlmy_posttarget" value="order_rev" />
                    <input type="hidden" name="media_id" value="<?php echo $context->media->id ?>" />
                    <input type="submit" value="Order captions">
                </form>
            </div>
            <div class="wdn-col-one-half">
                <h2>Pull captions from amara</h2>
                <p>
                    <a href="http://amara.org">amara.org</a> is a free service which helps you caption videos. To caption your video you will need to do the following.
                </p>
                <ol>
                    <li>Go to amara.org and create/edit captions for the video: <a class="wdn-button wdn-button-brand" href="<?php echo $context->getEditCaptionsURL(); ?>">Edit Captions on amara</a></li>
                    <li>Follow the instructions on amara.org to publish the new captions</li>
                    <li>Come back here, and click the button to 'pull captions from amara.org'</li>
                </ol>
                <form method="post">
                    <ul>
                        <li>
                            <label>
                                Revision Notes
                                <textarea name="comments" cols="12"></textarea>
                            </label>
                        </li>
                    </ul>
                    
                    <input type="hidden" name="__unlmy_posttarget" value="pull_amara" />
                    <input type="hidden" name="media_id" value="<?php echo $context->media->id ?>" />
                    <input type="submit" value="Pull Captions from amara.org">
                </form>
            </div>
        </div>
        
        <h2>Order history and status</h2>
        <p>View the current status of your orders</p>
        <table>
            <thead>
                <tr>
                    <th>Date of order</th>
                    <th>Requester</th>
                    <th>Status of order</th>
                    <th>Estimate</th>
                </tr>
            </thead>
            <tbody>
            <?php $orders = $context->getRevOrderHistory()->items; ?>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td>
                        <?php echo $order->datecreated ?>
                    </td>
                    <td>
                        <?php echo $order->uid ?>
                    </td>
                    <td>
                        <?php echo $order->status ?>
                        <?php if (!empty($order->dateupdated)): ?>
                            (<?php echo $order->dateupdated ?>)
                        <?php endif; ?>
                        <?php if (UNL_MediaHub_RevOrder::STATUS_ERROR == $order->status): ?>
                            -- <?php echo $order->error_text ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        $<?php echo $order->estimate ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        
        <h2>Caption track history</h2>
        <p>You can manage old captions.</p>
        <table>
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
                        <td>
                            <?php echo $track->datecreated ?>
                        </td>
                        <td>
                            <?php echo $track->source ?>
                        </td>
                        <td>
                            <?php echo $track->revision_comment ?>
                        </td>
                        <td>
                            <ul>
                                <?php foreach ($track->getFiles()->items as $file): ?>
                                    <li>
                                        <a href="<?php echo $file->getURL() ?>&amp;download=1" target="_blank"><?php echo $file->language ?>.<?php echo $file->format ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td>
                            <?php if ($context->media->media_text_tracks_id == $track->id): ?>
                                (active)
                            <?php else: ?>
                                <form method="post">
                                    <input type="hidden" name="__unlmy_posttarget" value="set_active_text_track" />
                                    <input type="hidden" name="media_id" value="<?php echo $context->media->id ?>" />
                                    <input type="hidden" name="text_track_id" value="<?php echo $track->id ?>" />
                                    <input type="submit" value="Set Active">
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div>
            <h2>Get Help</h2>
            <p>
                If you have questions or comments, please email one of the following:
            </p>
            <ul>
                <li>Technical question or issues: Please use the 'Email Us' tab on this page</li>
                <li>Billing: <a href="mailto:btessalee2@unl.edu">Brittany Tessalee</a></li>
            </ul>
        </div>
    </div>
</div>
