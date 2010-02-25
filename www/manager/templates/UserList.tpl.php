<h1>Users</h1>
<ul>
<?php
foreach ($context->items as $user) {
    echo '<li>'.$user->uid.'</li>';
}
?>
</ul>
<h2>Add User</h2>
<form action="" method="post" name="add_user" id="add_user">
    <div style="display: none;">
        <input type="hidden" id="feed_id" name="feed_id" value="<?php echo (int)$_GET['feed_id']; ?>" />
        <input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="feed_users" />
    </div>

    <fieldset id="addhead" class="daddhead_class">

        <legend>Add New User</legend>
        <ol>
            <li><label for="uid" class="element">User Id (like jdoe2):</label><div class="element"><input id="uid" name="uid" type="text" /></div></li>
            <li><label for="submit" class="element">&nbsp;</label><div class="element"><input id="submit" name="submit" value="Add User" type="submit" /></div></li>
        </ol>
    </fieldset>
</form>