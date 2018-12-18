<div class="dcf-bleed">
    <div class="dcf-wrapper dcf-pt-0">
        <h1>Caption Order Details for: <?php echo $context->media->title ?>, order number: <?php echo $context->order->id ?> </h1>
        <a href="<?php echo UNL_MediaHub_Controller::getURL() . 'manager/?view=addmedia&id=' . $context->media->id?>" class="dcf-btn">Edit Media</a>
        <a href="<?php echo UNL_MediaHub_Controller::getURL() . 'manager/?view=editcaptions&id='. $context->media->id?>" class="dcf-btn">Manage Captions</a>
        <a href="<?php echo $context->media->getURL()?>" class="dcf-btn">View Media</a>
    </div>
</div>
<div class="dcf-bleed">
    <div class="dcf-wrapper dcf-pt-0">
        <div class="dcf-grid dcf-col-gap-6">
<?php if ($context->order->isSuccess()): ?>
            <div class="dcf-col-100% dcf-col-67%-start@sm">
                <form method="post" id="download_format">
                    <h2 class="clear-top">Download captions</h2>
                    <label class="dcf-label" for="format">Select caption format</label>
                    <div class="dcf-input-select">
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
                    </div>
                    <input type="hidden" name="__unlmy_posttarget" value="download_rev" />
                    <input type="hidden" name="order_id" value="<?php echo (int)$context->order->id ?>" />
                    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
                    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">
                    <input type="submit" value="Download">
                </form>
            </div>
<?php endif; ?>
            <div class="dcf-col-100% dcf-col-33%-start@sm">
                <dl>
                    <dt>Order Number</dt>
                    <dd><?php echo (int)$context->order->id ?></dd>
                    <dt>Date created</dt>
                    <dd><?php echo UNL_MediaHub::escape($context->order->datecreated) ?></dd>
                    <dt>Last Updated</dt>
                    <dd><?php echo UNL_MediaHub::escape($context->order->dateupdated) ?></dd>
                    <dt>Cost Object Number</dt>
                    <dd><?php echo UNL_MediaHub::escape($context->order->costobjectnumber) ?></dd>
                    <dt>Created By</dt>
                    <dd><?php echo UNL_MediaHub::escape($context->order->uid) ?></dd>
                    <dt>Status of order</dt>
                    <dd><?php echo UNL_MediaHub::escape($context->order->status) ?></dd>
                    <dt>Cost</dt>
                    <dd><?php echo UNL_MediaHub::escape($context->order->estimate) ?></dd>
                </dl>
            </div>
        </div>
    </div>
</div>
