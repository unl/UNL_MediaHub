<h1>User Manager</h1>
<h2><?php //@todo put title of channel here ?></h2>
<div class="two_col left">
<h3 class="zenform cool">Add User</h3>
<form action="" method="post" name="add_user" id="add_user" class="zenform cool" style="margin-top:-6px;">
    <fieldset id="addhead" class="daddhead_class">
        <legend>Give us an ID</legend>
        <ol>
            <li><label for="uid" class="element"><span class="required">*</span>User Id <span class="helper">(like jdoe2)</span></label><div class="element"><input id="uid" name="uid" type="text" /></div></li>
            <li><label for="submit" class="element">&nbsp;</label><div class="element"><input id="submit" name="submit" value="Add User" type="submit" /></div></li>
        </ol>
        <div style="display: none;">
	        <input type="hidden" id="feed_id" name="feed_id" value="<?php echo (int)$_GET['feed_id']; ?>" />
	        <input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="feed_users" />
        </div>
    </fieldset>
</form>
</div>
<div class="two_col right">
<h3>Users with Access</h3>
<ul id="userList">
<?php
foreach ($context->items as $user) {
    echo '<li><img class="profile_pic medium" src="http://planetred.unl.edu/pg/icon/unl_'.$user->uid.'/medium/" />'.UNL_Services_Peoplefinder::getFullName($user->uid) .'<span class="uid">('.$user->uid.')</span></li>';
}
?>
</ul>
</div>