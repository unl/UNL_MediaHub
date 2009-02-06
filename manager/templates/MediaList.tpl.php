<?php
$addMediaURL = UNL_MediaYak_Manager::getURL().'?view=addmedia';
if (isset($_GET['id'])) {
    $addMediaURL .= '&amp;feed_id='.$_GET['id'];
}
?><p><a class="add_media" href="<?php echo $addMediaURL; ?>">Add media</a></p> 
<?php
if (count($this->items)) {
    echo '<ul>';
    foreach ($this->items as $media) {
        echo '
        <li>
            '.$media->title.'
            <a class="edit" href="'.$addMediaURL.'&amp;id='.$media->id.'">Edit Details</a>
        </li>';
    }
    echo '</ul>';
} else {
    echo '<p>This feed has no media yet.</p>';
}
?>
<p><a class="add_media" href="<?php echo $addMediaURL; ?>">Add media</a></p> 
