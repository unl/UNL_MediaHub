<?php
$type = 'audio';
$height = 358;
if (UNL_MediaYak_Media::isVideo($this->content_type)) {
    $type = 'video';
    $dimensions = UNL_MediaYak_Media::getMediaDimensions($this->id);
    // Scale everything down to 358 wide
    $height = (450/$dimensions['width'])*$dimensions['height']+20;
}
UNL_MediaYak_Controller::setReplacementData('title', 'UNL | Media Hub | '.htmlspecialchars($this->title));
UNL_MediaYak_Controller::setReplacementData('breadcrumbs', '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaYak_Controller::getURL().'">Media</a></li> <li>'.htmlspecialchars($this->title).'</li></ul>');
$meta = '
<meta name="title" content="'.htmlentities($this->title, ENT_QUOTES).'" />
<meta name="description" content="'.htmlentities($this->description, ENT_QUOTES).'" />
<link rel="image_src" href="'.UNL_MediaYak_Controller::$thumbnail_generator.urlencode($this->url).'" />
<meta name="medium" content="'.$type.'" />';
if ($type == 'video') {
    $meta .= '<link rel="video_src" href="'.$this->url.'" />';
}
UNL_MediaYak_Controller::setReplacementData('head', $meta);
?>
<div class="two_col left">
    <div class="content_holder" id="preview_holder">
        <div class="unl_liquid_pictureframe">
          <div class="unl_liquid_pictureframe_inset">
            <div id="preview"></div>
          <span class="unl_liquid_pictureframe_footer"></span>
          </div>
        </div>
    </div>
    <script type="text/javascript">writeUNLPlayer('<?php echo $this->url; ?>','preview', 450, <?php echo $height; ?>);</script>
    <div id="comments">
    <h3><?php echo count($this->UNL_MediaYak_Media_Comment); ?> Comments | <a href="#commentForm">Leave Yours</a></h3>
    <?php
    if (count($this->UNL_MediaYak_Media_Comment)) {
        echo '<ul>';
        foreach ($this->UNL_MediaYak_Media_Comment as $comment) {
            echo '<li>';
            if ($name = UNL_Services_Peoplefinder::getFullName($comment['uid'])) {
                
            }
            echo '<h4>'.$name.'</h4>';
            echo '<p><em>'.date('m/d/y g:i a', strtotime($comment['datecreated'])).'</em></p>';
            echo '<blockquote>'.htmlentities(strip_tags($comment['comment']), ENT_QUOTES).'</blockquote>';
            echo '</li>';
        }
        echo '</ul>';
    }?>
    </div>
    <?php
    if (UNL_MediaYak_Controller::isLoggedIn()) {
        // show the form!
        $form = new UNL_MediaYak_Media_Comment_Form();
        UNL_MediaYak_OutputController::display($form);
    } else {
        echo '<a href="https://login.unl.edu/cas/login?service='.urlencode(UNL_MediaYak_Controller::getURL()).'media/'.$this->id.'">Log in to post comments</a>';
    }?>
</div>
<div class="col right">
  <h2><?php echo $this->title; ?></h2>
  <?php
    if ($element = UNL_MediaYak_Feed_Media_NamespacedElements_itunes::mediaHasElement($this->id, 'subtitle')) {
        echo '<h3 class="itunes_subtitle">'.$element->value.'</h3>';
    }
    $summary = $this->description;
    if ($element = UNL_MediaYak_Feed_Media_NamespacedElements_itunes::mediaHasElement($this->id, 'summary')) {
        $summary .= '<span class="itunes_summary">'.$element->value.'</span>';
    }
    ?>
  <p><?php echo $summary; ?><br />
  <?php if (!empty($this->author)) { ?><span class="author">From:</span> <?php echo $this->author; ?><br />
  <?php } ?>
  <span class="addedDate">Added:</span> <?php echo date('m/d/Y', strtotime($this->datecreated)); ?>
  </p>
    <!-- ADDTHIS BUTTON BEGIN -->
    <script type="text/javascript">
    addthis_pub             = 'unlwdn';
    addthis_brand           = 'UNL';
    addthis_options         = 'favorites, email, digg, delicious, myspace, facebook, google, live, more';
    </script>
    <a class="imagelink" href="http://www.addthis.com/bookmark.php" onmouseover="return addthis_open(this, '', '[URL]', '[TITLE]')" onmouseout="addthis_close()" onclick="return addthis_sendto()"><img src="http://s9.addthis.com/button1-share.gif" width="125" height="16" border="0" alt="" /></a>
    <script type="text/javascript" src="http://s7.addthis.com/js/152/addthis_widget.js"></script>
    <!-- ADDTHIS BUTTON END -->
    <h6>Embed</h6>
    <textarea cols="25" rows="3" onclick="this.select(); return false;"><?php
        echo htmlentities('<div class="content_holder" id="preview_holder"><div class="unl_liquid_pictureframe"><div class="unl_liquid_pictureframe_inset"><object id="preview" height="'.$height.'" width="450"><param value="true" name="allowfullscreen"></param><param value="always" name="allowscriptaccess"></param><embed src="http://www.unl.edu/ucomm/templatedependents/templatesharedcode/scripts/components/mediaplayer/player.swf?file='.$this->url.'&amp;image='.UNL_MediaYak_Controller::$thumbnail_generator.urlencode($this->url).'&amp;volume=100&amp;autostart=false" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" height="'.$height.'" width="450"></embed></object><span class="unl_liquid_pictureframe_footer"></span></div></div></div>');
    ?></textarea>
    
    <h6 style="margin-top:1em;"><a href="<?php echo $this->url; ?>" class="video-x-generic">Download this media file</a></h6>
</div>