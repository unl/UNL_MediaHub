 
<form action="<?php echo $context->action; ?>" method="post" id="commentForm" class="zenform cool">
    <fieldset>
        <legend>Leave your comment</legend>
        <ol>
            <li><label for="comment">Comment</label><textarea id="comment" name="comment" cols="50" rows="3"></textarea></li>
        </ol>
    </fieldset>
    <input id="submit" name="submit" value="Submit" type="submit" />
</form>