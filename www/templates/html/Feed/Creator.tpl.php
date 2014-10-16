<?php
$creator = @UNL_Services_Peoplefinder::getFullName($context->uidcreated);
if ($creator) {
    $creator = '<a href="http://directory.unl.edu/?uid='.$context->uidcreated.'" class="wdnPeoplefinder" title="'.$creator.'\'s Directory Record">'.$creator.'</a>';
} else {
    $creator = $context->uidcreated;
}
?>
<p class="mh-feed-author">This channel was created by <?php echo $creator; ?></p>