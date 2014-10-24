<?php
$page->addScript(UNL_MediaHub_Controller::getURL() . 'templates/html/scripts/plupload/plupload.full.min.js');
?>

<div class="wdn-band wdn-light-neutral-band">

  <div class="wdn-inner-wrapper">
    <h1 class="wdn-brand">Manage Media</h1>

    <form action="?" method="post"><div class="wdn-grid-set">

      <div id="mh_upload_media_container" class="wdn-col-three-sevenths">
          <a id="mh_upload_media" class="mh-upload-box wdn-center" href="javascript:;">
              <h2>+<span class="wdn-subhead">Add Media</span></h2>
              <p>.mp4, .mov, .mp3, .wav, .aac</p>
          </a>
          <div id="filelist" class="mh-upload-box wdn-center">
              Your browser doesn't have Flash, Silverlight or HTML5 support.
          </div>
      </div>

      <div class="wdn-col-four-sevenths">
        <div class="wdn-grid-set">
          <div class="wdn-col-three-fifths">
            <ol>
              <li class="clear-top">
                <label for="title">
                  Title
                  <span class="required">*</span>
                </label>
                <input type="text" id="title" name="title">
              </li>
              <li>
                <label for="author">
                  Author
                  <span class="required">*</span>
                  <span class="helper">Name of media creator</span>
                </label>
                <input type="text" id="author" name="author">
              </li>
              <li>
                <label for="description">
                  Description
                  <span class="required">*</span>
                </label>
                <textarea rows="4" type="text" id="description" name="description">Explain what this media is all about. Use a few sentences, but keep it to 1 paragraph.
                </textarea>
              </li>
              <input type="submit" name="publish" value="Publish">
            </ol>
          </div>
          <div class="wdn-col-two-fifths">
            <ol>
              <li class="clear-top">

                <label for="privacy">Privacy</label>
                <span class="wdn-icon-info mh-tool-tip">
                  <div>
                    <ul>
                      <li><span class="heading">Public</span> - Anyone can access the media. </li>
                      <li><span class="heading">Unlisted</span> - Media will not be included in public MediaHub listings.</li> 
                      <li><span class="heading">Private</span> - Only members of channels that the media is included in can access it.</li>
                    </ul>
                  </div>
                </span>

                <select name="privacy" id="privacy">
                  <option value="Public" selected="selected">Public</option>
                  <option value="unlisted">Unlisted</option>
                  <option value="Privacy">privacy</option>
                </select>
              </li>


              <li>
                <label for="channels">Channels
                  <span class="required">*</span>
                </label>
                <div class="mh-channel-box">                  
                  <ul>

                    <li><input type="checkbox" name="channels" value="Bike">This is a channel name</li>
                    <li><input type="checkbox" name="channels" value="Car">Peanut butter biscuits</li> 
                    <li><input type="checkbox" name="channels" value="Car">Spencer's Channel</li>    
                    <li><input type="checkbox" name="channels" value="Car">Okay then</li> 
                    <li><input type="checkbox" name="channels" value="Car">Peanut butter</li> 
                    <li><input type="checkbox" name="channels" value="Car">WOO butter</li> 
                    <li><input type="checkbox" name="channels" value="Car">Peanut gutter</li> 
                    <li><input type="checkbox" name="channels" value="Car">Okay then</li> 
                    <li><input type="checkbox" name="channels" value="Car">Peanut butter</li> 

                  </ul>
                </div>
              </li>
            </ol>

          </div>
        </div>
      </div>

    </div>
  </form>               

</div>

</div>

<script type="text/javascript">
WDN.initializePlugin('tooltip');
</script>


<script type="text/javascript">
    // Custom example logic

    var max_file_count = 1;
    var uploader = new plupload.Uploader({
        runtimes : 'html5,flash,silverlight,html4',
        browse_button : 'mh_upload_media', // you can pass in id...
        container: document.getElementById('mh_upload_media_container'), // ... or DOM Element itself
        url : '<?php echo UNL_MediaHub_Controller::getURL()?>manager/?format=json',
        flash_swf_url : '<?php echo UNL_MediaHub_Controller::getURL()?>templates/html/scripts/plupload/Moxie.swf',
        silverlight_xap_url : '<?php echo UNL_MediaHub_Controller::getURL()?>templates/html/scripts/plupload/Moxie.xap',
        drop_element: "mh_upload_media",
        chunk_size: '1mb',
        multi_selection:false,
        multipart_params: {
            __unlmy_posttarget: 'upload_media'
        },
        filters : {
            max_file_size : '900mb',
            mime_types: [
                {title : "Video files", extensions : "mp4,mov,mp3"},
                {title : "Audio files", extensions : "mp3"}
            ]
        },

        init: {
            PostInit: function() {
                WDN.jQuery('#filelist').text('').hide();
            },

            FilesAdded: function(up, files) {
                plupload.each(files, function(file) {
                    if (up.files.length > max_file_count) {
                        up.removeFile(file);
                        return;
                    }
                    
                    document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
                });
                uploader.disableBrowse();
                WDN.jQuery('#mh_upload_media').hide();
                WDN.jQuery('#filelist').show();
                uploader.start();
            },

            UploadProgress: function(up, file) {
                document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
            },

            Error: function(up, err) {
                alert("Error #" + err.code + ": " + err.message);
            }
        }
    });

    uploader.init();

</script>