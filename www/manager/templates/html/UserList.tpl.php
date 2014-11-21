<h1><?php if (isset($context->options['feed'])) { echo $context->options['feed']->title; } ?> Channel - User Manager</h1>
<div class="four_col"></div>
<ul id="userList">
<?php
foreach ($context->items as $user) {
    echo '<li><img class="profile_pic medium" src="http://planetred.unl.edu/pg/icon/unl_'.$user->uid.'/medium/" />'.@UNL_Services_Peoplefinder::getFullName($user->uid) .'<span class="uid">('.$user->uid.')</span>';
    echo $savvy->render($user, 'DeleteUserForm.tpl.php');
    echo '</li>';
}
?>
</ul>
    <form action="?view=newsroom" method="post" id="addUser" class="addData">
        <input type="hidden" id="feed_id" name="feed_id" value="<?php echo (int)$_GET['feed_id']; ?>" />
        <input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="feed_users" />
        <label for="uid">My.UNL Username</label>
        <input id="uid" name="uid" type="text" />
        <input type="submit" value="Add User" />
    </form>
</div>