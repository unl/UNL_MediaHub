 
<form action="<?php echo $this->action; ?>" method="post" id="commentForm">
    <fieldset>
        <legend>Leave your comment</legend>
        <ol>
            <li><label for="comment" class="element">Comment</label><div class="element"><textarea id="comment" name="comment" cols="50" rows="3"></textarea></div></li>
            <li><label for="submit" class="element">&nbsp;</label><div class="element"><input id="submit" name="submit" value="Submit" type="submit" /></div></li>
        </ol>
    </fieldset>
</form>