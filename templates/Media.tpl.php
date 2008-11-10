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
  <p><?php echo htmlspecialchars(strip_tags($this->description)); ?><br />
  <?php if (!empty($this->author)) { ?><span class="author">From:</span> <?php echo $this->author; ?><br />
  <?php } ?>
  <span class="addedDate">Added:</span> <?php echo date('m/d/Y', strtotime($this->datecreated)); ?>
  </p>
</div>