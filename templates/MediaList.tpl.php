<h3 class="sec_main">UNL Media<?php
if (isset($this->label) && !empty($this->label)) {
    UNL_MediaYak_Controller::setReplacementData('title', 'UNL | Media | '.$this->label);
    UNL_MediaYak_Controller::setReplacementData('breadcrumbs', '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaYak_Controller::getURL().'">Media</a></li> <li>'.$this->label.'</li></ul>');
    echo ': '.$this->label;
}
?></h3>
<?php
if (count($this->media)) {
    $pager_layout   = new Doctrine_Pager_Layout($this->pager,
        new Doctrine_Pager_Range_Sliding(array('chunk'=>5)),
        UNL_MediaYak_Controller::getURL(null, array_merge($this->options, array('page'=>'{%page_number}'))));
    $pager_layout->setTemplate(' <a href="{%url}">{%page}</a> ');
    $pager_layout->setSelectedTemplate('{%page}');
    $pager_links = $pager_layout->display(null, true);
    ?>
        <ul>
    
        <?php
        foreach ($this->media as $media) { ?>
            <li>
            <div class="clr">
                <div><a href="<?php echo UNL_MediaYak_Controller::getURL($media); ?>"><img src="<?php echo UNL_MediaYak_Controller::$thumbnail_generator.$media->url; ?>" alt="Thumbnail preview for <?php echo $media->title; ?>" width="50" height="38" /></a></div>
                <h4><a href="<?php echo UNL_MediaYak_Controller::getURL($media); ?>"><?php echo htmlspecialchars($media->title); ?></a></h4>
                <?php
                $summary = $media->description;
                if (strlen($summary) >= 250) {
                    $summary = substr($summary, 0, 250).'&hellip;';
                }
                $summary = strip_tags($summary, '<a><img>');
                ?>
                <p><?php echo $summary; ?></p>
            </div>
            </li>
        <?php  
        } ?>
        </ul>
        <em>Displaying <?php echo $this->first; ?> through <?php echo $this->last; ?> out of <?php echo $this->total; ?></em>
    <div class="pager_links"><?php echo $pager_links; ?></div>
<?php
} else {
    echo '<p>Sorry, no media could be found</p>';
}
?>