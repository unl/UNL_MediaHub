<?php
$type = 'video';
if (substr($this->url, -4) == '.mp3') {
    $type = 'audio';
}
UNL_MediaYak_Controller::setReplacementData('title', 'UNL | Media Hub | '.htmlspecialchars($this->title));
UNL_MediaYak_Controller::setReplacementData('breadcrumbs', '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaYak_Controller::getURL().'">Media</a></li> <li>'.htmlspecialchars($this->title).'</li></ul>');
$meta = '
<meta name="title" content="'.htmlentities($this->title, ENT_QUOTES).'" />
<meta name="description" content="'.htmlentities($this->description, ENT_QUOTES).'" />
<link rel="image_src" href="'.UNL_MediaYak_Controller::$thumbnail_generator.$this->url.'" />
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
    <script type="text/javascript">writeUNLPlayer('<?php echo $this->url; ?>','preview');</script>
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
  <p><?php echo $this->description; ?><br />
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
        echo htmlentities('<div class="content_holder" id="preview_holder"><div class="unl_liquid_pictureframe"><div class="unl_liquid_pictureframe_inset"><object style="visibility: visible;" id="preview" data="/ucomm/templatedependents/templatesharedcode/scripts/components/mediaplayer/player.swf" type="application/x-shockwave-flash" height="358" width="450"><param value="true" name="allowfullscreen"><param value="always" name="allowscriptaccess"><param value="file='.$this->url.'&amp;image='.UNL_MediaYak_Controller::$thumbnail_generator.$this->url.'&amp;volume=100&amp;autostart=true" name="flashvars"></object><span class="unl_liquid_pictureframe_footer"></span></div></div></div>');
    ?></textarea>
</div>