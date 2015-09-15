<div class="wdn_band">
    <div class="wdn_inner_wrapper">
        <h1>Edit Captions</h1>
        <a href="<?php echo UNL_MediaHub_Controller::getURL() . 'manager/?view=addmedia&id=' . $context->media->id?>" class="wdn-button">Go back to editing the video</a>
        <div class="wdn-grid-set">
            <div class="wdn-col-one-half">
                <h2>Order captions from rev.com</h2>
                <form method="post">
                    <div class="important-notice">
                        <strong>Important</strong>: captions cost $1 per video minute.  Example: A 3:15 minute video would cost $4.
                        
                        <?php if ($duration = $context->media->findDuration()): ?>
                            Estimated cost:
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
                                        <td>$<?php echo ceil($duration['seconds']/60) ?></td>
                                    </tr>
                                </tbody>
                            </table>
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
                    <input type="submit" value="Order captions from rev.com">
                </form>
            </div>
            <div class="wdn-col-one-half">
                <h2>Pull cpations from amara</h2>
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
                    <input type="submit" value="Pull Captions from amara.com">
                </form>
            </div>
        </div>
        
        <h2>Rev.com order history and status</h2>
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
                                        <a href="<?php echo $file->getURL() ?>" target="_blank"><?php echo $file->language ?>.<?php echo $file->format ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td>
                            (make active)
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
