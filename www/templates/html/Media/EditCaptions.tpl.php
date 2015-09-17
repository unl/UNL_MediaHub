<div class="wdn_band">
    <div class="wdn_inner_wrapper">
        <h1>Edit Captions</h1>
        <a href="<?php echo UNL_MediaHub_Controller::getURL() . 'manager/?view=addmedia&id=' . $context->media->id?>" class="wdn-button">Go back to editing the video</a>
        <div class="wdn-grid-set">
            <div class="wdn-col-one-half">
                <h2>Order captions</h2>
                <form method="post">
                    <div class="important-notice">
                        <strong>Important</strong>: captions cost $1 per video minute.  Example: A 3:15 minute video would cost $4.

                        <p>
                            We will manually caption this media for you. Orders are usually completed within 24 hours.  Note that orders can not be canceled.
                        </p>
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
                                Cost Object Jumber
                                <input type="text" name="cost_object" required />
                            </label>
                        </li>
                        <li>
                            <label>
                                Comments
                                <textarea name="comments" cols="12"></textarea>
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
                    <a href="http://amara.org">amara.org</a> is a free service which helps you caption videos. To caption your video, simply click the link below, and follow the steps on amara.org.  Once you are done editig, come back here and click the button to 'pull captions from amara.org'.
                    <br />
                    <a class="wdn-button wdn-button-brand" href="<?php echo $context->getEditCaptionsURL(); ?>">Edit Captions on amara</a>
                </p>
                <form method="post">
                    <ul>
                        <li>
                            <label>
                                Comments
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
    </div>
</div>
