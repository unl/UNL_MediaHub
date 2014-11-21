<h1><?php if (isset($context->options['feed'])) { echo $context->options['feed']->title; } ?> Channel - User Manager</h1>

<ul id="userList">
    <?php foreach ($context->items as $user):?>
        <li>
            <img class="profile_pic medium" src="http://planetred.unl.edu/pg/icon/unl_<?php echo str_replace('-', '_', $user->uid) ?>/medium/" />
            <?php echo @UNL_Services_Peoplefinder::getFullName($user->uid); ?>
            <span class="uid"><?php echo $user->uid ?></span>
            <?php echo $savvy->render($user, 'DeleteUserForm.tpl.php'); ?>
        </li>
    <?php endforeach ?>
</ul>
<form action="?view=newsroom" method="post" id="addUser" class="addData">
    <input type="hidden" id="feed_id" name="feed_id" value="<?php echo (int)$_GET['feed_id']; ?>" />
    <input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="feed_users" />
    <label for="uid">My.UNL Username</label>
    <input id="uid" name="uid" type="text" />
    <input type="submit" value="Add User" />
</form>