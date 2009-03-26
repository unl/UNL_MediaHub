<?php
$addMediaURL = UNL_MediaYak_Manager::getURL().'?view=addmedia';
if (isset($_GET['id'])) {
    $addMediaURL .= '&amp;feed_id='.$_GET['id'];
}
?><p><a class="add_media" href="<?php echo $addMediaURL; ?>">Add media</a></p> 
<?php
if (count($this->items)) {
    echo '<ul class="medialist">';
    foreach ($this->items as $media) { ?>
        <li>
            <div><a href="<?php echo UNL_MediaYak_Controller::getURL($media); ?>"><img class="thumbnail" src="<?php echo UNL_MediaYak_Controller::$thumbnail_generator.urlencode($media->url); ?>" alt="Thumbnail preview for <?php echo $media->title; ?>" width="50" height="38" /></a></div>
            <h4><a href="<?php echo UNL_MediaYak_Controller::getURL($media); ?>"><?php echo htmlspecialchars($media->title); ?></a></h4>
            <?php
            if ($element = UNL_MediaYak_Feed_Media_NamespacedElements_itunes::mediaHasElement($media->id, 'subtitle')) {
                echo '<h5 class="itunes_subtitle">'.$element->value.'</h5>';
            }
            $summary = $media->description;
            if ($element = UNL_MediaYak_Feed_Media_NamespacedElements_itunes::mediaHasElement($media->id, 'summary')) {
                $summary .= '<span class="itunes_summary">'.$element->value.'</span>';
            }
            if (strlen($summary) >= 250) {
                $summary = substr($summary, 0, 250).'&hellip;';
            }
            $summary = strip_tags($summary, '<a><img>');
            $summary = str_replace('Related Links', '', $summary);
            ?>
            <p><?php echo $summary; ?></p>
            <a class="edit" href="<?php echo $addMediaURL; ?>&amp;id=<?php echo $media->id; ?>">Edit Details</a>
        
        </li>
    <?php  
    } 
    echo '</ul>';
} else {
    echo '<p>This feed has no media yet.</p>';
}
?>
<p><a class="add_media" href="<?php echo $addMediaURL; ?>">Add media</a></p> 
