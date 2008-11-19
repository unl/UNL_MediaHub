<?php
UNL_MediaYak_Controller::setReplacementData('title', 'UNL | Media | '.htmlentities($this->title));
UNL_MediaYak_Controller::setReplacementData('breadcrumbs', '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaYak_Controller::getURL().'">Media</a></li> <li>'.htmlentities($this->title).'</li></ul>');

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
</div>