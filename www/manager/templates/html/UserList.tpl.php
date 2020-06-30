<div class="dcf-pt-6 dcf-pb-6">
  <h2><?php if (isset($context->options['feed'])) { echo UNL_MediaHub::escape($context->options['feed']->title); } ?> Channel - User Manager</h2>

  <ul class="dcf-list-inline" id="userList">
      <?php foreach ($context->items as $user):?>
          <li>
              <img class="profile_pic medium" src="http://planetred.unl.edu/pg/icon/unl_<?php echo str_replace('-', '_', $user->uid) ?>/medium/" />
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
  <form action="?view=newsroom" method="post" id="addUser" class="dcf-form addData">
      <input type="hidden" id="feed_id" name="feed_id" value="<?php echo (int)$_GET['feed_id']; ?>" />
      <input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="feed_users" />
      <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
      <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">
      <label for="uid">My.UNL Username</label>
      <div class="dcf-input-group">
        <input id="uid" name="uid" type="text" />
        <input type="submit" value="Add User" />
      </div>
  </form>
</div>