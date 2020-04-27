 
<form action="<?php echo $context->action; ?>" method="post" id="commentForm" class="zenform cool">
    <ol>
        <li><label class="dcf-label" for="comment">Comment</label><textarea class="dcf-input-text" id="comment" name="comment" cols="50" rows="3"></textarea></li>
    </ol>
    <input id="submit" name="submit" value="Submit" type="submit" />
    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">
</form>