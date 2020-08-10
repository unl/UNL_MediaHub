<?php
$page->addScript(UNL_MediaHub_Controller::getURL() . 'templates/html/scripts/parser.js?v=' . UNL_MediaHub_Controller::getVersion(), NULL, TRUE);
?>
<div class="dcf-bleed dcf-pt-6 dcf-pb-6">
    <div class="dcf-wrapper">
        <h2 class="dct-txt-h4">Edit Caption Track for: <?php echo UNL_MediaHub::escape($context->media->title) ?></h2>
        <a href="<?php echo UNL_MediaHub_Controller::getURL() . 'manager/?view=editcaptions&id=' . (int)$context->media->id?>" class="dcf-btn dcf-btn-primary">Back To Media Captions</a>
    </div>
</div>

<div class="dcf-bleed unl-bg-lightest-gray dcf-pt-6 dcf-pb-6">
    <div class="dcf-wrapper">
        <div class="dcf-grid dcf-col-gap-vw dcf-row-gap-6">
            <dl class="dcf-col-100% dcf-col-25%-start@sm">
                <dt>Created</dt>
                <dd><?php echo UNL_MediaHub::escape($context->track->datecreated); ?></dd>
                <dt>Source</dt>
                <dd><?php echo UNL_MediaHub::escape($context->track->source); ?></dd>
                <dt>Revision Comment</dt>
                <dd><?php echo UNL_MediaHub::escape($context->track->revision_comment) ?></dd>
            </dl>
            <div class="dcf-col-100% dcf-col-75%-end@sm">
                <form class="dcf-form" method="POST">
                    <input type="hidden" name="__unlmy_posttarget" value="update_text_track_file" />
                    <input type="hidden" name="media_id" value="<?php echo (int)$context->media->id ?>" />
                    <input type="hidden" name="track_id" value="<?php echo (int)$context->track->id ?>" />
                    <input type="hidden" name="track_file_id" value="<?php echo (int)$context->trackFile->id ?>" />
                    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
                    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">

                    <div class="dcf-form-group">
                        <label for="track-contents">Track Contents</label>
                        <textarea id="track-contents" name="file_contents" cols="60" rows="5" oninput="test()"><?php echo trim($context->trackFile->file_contents);?></textarea>
                    </div>
                    <p id="status"></p>
                    <ol></ol>
                    <pre></pre>
                    <input class="dcf-btn dcf-btn-primary" name="submit" type="submit" value="Save Captions">
                </form>
            </div>
        </div>
    </div>
</div>
<script>

  function test() {
    var pa = new WebVTTParser();
    var r = pa.parse(document.getElementById('track-contents').value, 'subtitles/captions/descriptions');

    var ol = document.getElementsByTagName('ol')[0];
    var p = document.getElementById('status');
    var pre = document.getElementsByTagName('pre')[0];
    ol.textContent = '';

    if(r.errors.length > 0) {
      if(r.errors.length == 1) {
        p.textContent = 'Almost there!';
      } else if(r.errors.length < 5) {
        p.textContent = 'Not bad, keep at it!';
      } else {
        p.textContent = 'You are hopeless, RTFS.';
      }
      p.style = 'color: red';

      for(var i = 0; i < r.errors.length; i++) {
        var error = r.errors[i];
        var message = 'Line ' + error.line;
        var li = document.createElement('li');

        if(error.col) {
          message += ", column " + error.col;
        }
        li.textContent = message + ": " + error.message;
        ol.appendChild(li);
      }
    } else {
      p.textContent = "Awesome, your WebVTT is valid!";
      p.style = 'color: green';
    }
    p.textContent += " (" + r.time + "ms)";
    var s = new WebVTTSerializer();
    pre.textContent = s.serialize(r.cues);
  }
  test();
  function debug(url) {
    var hmm = url.slice(url.indexOf('#')) == "#debug";
    document.getElementsByTagName('pre')[0].hidden = hmm ? false : true;
  }
  onhashchange = function(e) { debug(e.newURL) }
  debug(location.href);
</script>

