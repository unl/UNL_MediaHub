<?php
$creator = @UNL_Services_Peoplefinder::getFullName($context->uidcreated);
if ($creator) {
    $creator = '<a href="http://directory.unl.edu/?uid='.$context->uidcreated.'" class="wdnPeoplefinder" title="'.$creator.'\'s Directory Record">'.$creator.'</a>';
} else {
    $creator = $context->uidcreated;
}
?>
<h6 class="subhead">This channel was created by <?php echo $creator; ?> on <?php echo date("F j, Y, g:i a", strtotime($context->datecreated));?>.</h6>