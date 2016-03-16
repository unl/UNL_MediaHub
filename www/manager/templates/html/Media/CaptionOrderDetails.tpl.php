<div class="wdn-band">
    <div class="wdn-inner-wrapper wdn-inner-padding-no-top">
        <h1>Caption Order Details for: <?php echo $context->media->title ?>, order number: <?php echo $context->order->id ?> </h1>
        <a href="<?php echo UNL_MediaHub_Controller::getURL() . 'manager/?view=addmedia&id=' . $context->media->id?>" class="wdn-button">Edit Media</a>
        <a href="<?php echo UNL_MediaHub_Controller::getURL() . 'manager/?view=editcaptions&id='. $context->media->id?>" class="wdn-button">Manage Captions</a>
        <a href="<?php echo $context->media->getURL()?>" class="wdn-button">View Media</a>
    </div>
</div>
<div class="wdn-band">
    <div class="wdn-inner-wrapper wdn-inner-padding-no-top">
        <div class="wdn-grid-set">
<?php if ($context->order->isSuccess()): ?>
            <div class="bp2-wdn-col-two-thirds">
                <form method="post" id="download_format">
                    <h2 class="clear-top">Download captions</h2>
                    <label for="format">Select caption format</label>
                    <select id="format" name="format">
                        <option value="srt">.srt</option>
                        <option value="scc">.scc</option>
                        <option value="ttml">.ttml</option>
                        <option value="dfxp">.dfxp</option>
                        <option value="qt.txt">.qt.txt</option>
                        <option value="txt">.txt</option>
                        <option value="vtt">.vtt</option>
                        <option value="mcc">.mcc</option>
                        <option value="cap">.cap</option>
                        <option value="stl">.stl</option>
                    </select>
                    <input type="hidden" name="__unlmy_posttarget" value="download_rev" />
                    <input type="hidden" name="order_id" value="<?php echo $context->order->id ?>" />
                    <input type="submit" value="Download">
                </form>
            </div>
<?php endif; ?>
            <div class="bp2-wdn-col-one-third">
                <dl>
                    <dt>Order Number</dt>
                    <dd><?php echo $context->order->id ?></dd>
                    <dt>Date created</dt>
                    <dd><?php echo $context->order->datecreated ?></dd>
                    <dt>Last Updated</dt>
                    <dd><?php echo $context->order->dateupdated ?></dd>
                    <dt>Cost Object Number</dt>
                    <dd><?php echo $context->order->costobjectnumber ?></dd>
                    <dt>Created By</dt>
                    <dd><?php echo $context->order->uid ?></dd>
                    <dt>Status of order</dt>
                    <dd><?php echo $context->order->status ?></dd>
                    <dt>Cost</dt>
                    <dd><?php echo $context->order->estimate ?></dd>
                </dl>
            </div>
        </div>
    </div>
</div>
