<h1 class="wdn-brand"><?php if (isset($context->options['feed'])) { echo UNL_MediaHub::escape($context->options['feed']->title); } ?> Channel - User Manager</h1>

<ul id="userList">
    <?php foreach ($context->items as $user):?>
        <li>
            <img class="profile_pic medium" src="http://planetred.unl.edu/pg/icon/unl_<?php echo str_replace('-', '_', $user->uid) ?>/medium/" />
            <?php $fullName = @UNL_Services_Peoplefinder::getFullName($user->uid); ?>
            <?php if($fullName): ?>
                <?php echo UNL_MediaHub::escape($fullName); ?>
            <?php else: ?>
                <span class="mh-unknown">Unknown User!</span>
            <?php endif; ?>
            <span class="uid"><?php echo UNL_MediaHub::escape($user->uid) ?></span>
            <?php echo $savvy->render($user, 'DeleteUserForm.tpl.php'); ?>
        </li>
    <?php endforeach ?>
</ul>
<form action="?view=newsroom" method="post" id="addUser" class="addData">
    <input type="hidden" id="feed_id" name="feed_id" value="<?php echo (int)$_GET['feed_id']; ?>" />
    <input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="feed_users" />
    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">
    <label for="uid">My.UNL Username</label><br>
    <input id="uid" name="uid" type="text" />
    <input type="submit" value="Add User" />
</form>