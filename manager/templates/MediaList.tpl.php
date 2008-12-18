<?php
if (count($this->items)) {
    echo 'Here are the items';
    foreach ($this->items as $media) {
        echo $media;
    }
} else {
    echo 'This feed has no media yet!';
}
$addMediaURL = UNL_MediaYak_Manager::getURL().'?view=addmedia';
if (isset($_GET['id'])) {
    $addMediaURL .= '&feed_id='.$_GET['id'];
}
?>
<p><a href="<?php echo $addMediaURL; ?>">Add media -</a></p> 