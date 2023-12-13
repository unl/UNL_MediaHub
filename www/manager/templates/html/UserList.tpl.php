<?php
    $feedID = (int)$_GET['feed_id'];
    $authUser = UNL_MediaHub_AuthService::getInstance()->getUser();
    if (!$context->options['feed']->userCanEdit($authUser)) {
	    throw new UNL_MediaHub_RuntimeException('You do not have permission to manage this channel.', 403);
    }
?>
<div class="dcf-pt-6 dcf-pb-6">
    <h1 class="dcf-txt-h2"><?php if (isset($context->options['feed'])) { echo UNL_MediaHub::escape($context->options['feed']->title); } ?> Channel - User Manager</h1>

    <ul class="dcf-p-1 dcf-list-bare dcf-list-inline dcf-txt-xs dcf-bg-overlay-dark">
        <li class="dcf-m-0">
            <a class="dcf-btn dcf-btn-inverse-tertiary" href="<?php echo UNL_MediaHub_Controller::getURL(); ?>channels/<?php echo $feedID; ?>">View Channel</a>
        </li>
        <li class="dcf-m-0">
            <a class="dcf-btn dcf-btn-inverse-tertiary" href="<?php echo UNL_MediaHub_Manager::getURL(); ?>?view=feedmetadata&amp;id=<?php echo $feedID; ?>">Edit Channel</a>
        </li>
        <li class="dcf-m-0">
            <a class="dcf-btn dcf-btn-inverse-tertiary" href="<?php echo UNL_MediaHub_Manager::getURL(); ?>?view=feedstats&amp;feed_id=<?php echo $feedID; ?>">View Channel Stats</a>
        </li>
    </ul>

    <ul class="dcf-list-inline" id="userList">
        <?php foreach ($context->items as $user):?>
        <li>
                  <img class="profile_pic medium dcf-w-10" src="https://directory.unl.edu/avatar/<?php echo $user->uid; ?>/" />
                  <div class="dcf-txt-xs dcf-txt-center">
                    <?php $fullName = @UNL_Services_Peoplefinder::getFullName($user->uid); ?>
                    <?php if($fullName): ?>
                        <?php echo UNL_MediaHub::escape($fullName); ?>
                    <?php else: ?>
                        <span class="mh-unknown">Unknown User!</span>
                    <?php endif; ?>
                    <span class="uid dcf-d-block dcf-txt-xs"><?php echo UNL_MediaHub::escape($user->uid) ?></span>
                    <?php echo $savvy->render($user, 'DeleteUserForm.tpl.php'); ?>
                  </div>
        </li>
        <?php endforeach ?>
    </ul>
    <form action="?view=newsroom" method="post" id="addUser" class="dcf-form">
        <input type="hidden" id="feed_id" name="feed_id" value="<?php echo $feedID; ?>" />
        <input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="feed_users" />
        <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
        <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">
        <label for="uid">My.UNL Username</label>
        <div class="dcf-input-group">
            <input id="uid" name="uid" type="text" />
            <button class="dcf-btn dcf-btn-primary" type="submit">Add User</button>
        </div>
    </form>
</div>
