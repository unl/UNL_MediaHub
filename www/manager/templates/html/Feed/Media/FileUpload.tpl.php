<?php
$page->addScript(UNL_MediaHub_Controller::getURL() . 'templates/html/scripts/plupload/plupload.full.min.js');
?>

<div class="wdn-band wdn-light-neutral-band mh-upload-band">
    <div class="wdn-inner-wrapper">
        <h1 class="wdn-brand clear-top">
            Manage Media
            <span class="wdn-subhead wdn-pull-right"><a href="<?php echo UNL_MediaHub_Controller::getURL() ?>help/media-prep">Preparing Your Media</a></span>
        </h1>
        <form action="?" method="post" id="add_media">
            <input type="hidden" name="__unlmy_posttarget" value="feed_media" />
            <input type="hidden" id="media_url" name="url" value="">
            <div class="wdn-grid-set">
                <div id="mh_upload_media_container" class="bp2-wdn-col-three-sevenths">
                    <div id="mh_upload_media" class="mh-upload-box wdn-center">
                        <h2>+<span class="wdn-subhead">Add Media</span></h2>
                        <p>.mp4 or .mp3</p>
                    </div>
                    <div id="filelist" class="mh-upload-box wdn-center">
                        Your browser doesn't have Flash, Silverlight or HTML5 support.
                    </div>
                </div>
                <div class="bp2-wdn-col-four-sevenths">
                    <div class="wdn-grid-set">
                        <div class="bp1-wdn-col-three-fifths">
                            <ol>
                                <li class="clear-top">
                                    <label for="title">
                                        Title
                                        <span class="required">*</span>
                                    </label>
                                    <input type="text" id="title" name="title" class="required-entry">
                                </li>
                                <li>
                                    <label for="author">
                                        Author
                                        <span class="required">*</span>
                                        <div class="mh-tooltip wdn-icon-info italic" id="author-details">
                                            <div>
                                                Name of media creator
                                            </div>
                                        </div>
                                    </label>
                                    <input type="text" id="author" name="author" class="required-entry" aria-describedby="author-details">
                                </li>
                                <li>
                                    <label for="description">
                                        Description
                                        <span class="required">*</span>
                                    </label>
                                    <div class="mh-tooltip wdn-icon-info italic" id="description-details">
                                        <div>
                                            Explain what this media is all about. Use a few sentences, but keep it to 1 paragraph.
                                        </div>
                                    </div>
                                    <textarea rows="4" type="text" id="description" name="description" class="required-entry" aria-describedby="description-details"></textarea>
                                </li>
                            </ol>
                        </div>
                        <div class="bp1-wdn-col-two-fifths">
                            <ol>
                                <li class="clear-top">
                                    <?php echo $savvy->render($context, 'Feed/Media/fields/privacy.tpl.php'); ?>
                                </li>
                                <li>
                                    <?php echo $savvy->render($context->feed_selection); ?>
                                </li>
                            </ol>
                        </div>                                                     
                    </div>
                    <input type="submit" id="publish" name="publish" value="Publish" class="wdn-button-brand" disabled="disabled"> 
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
WDN.initializePlugin('form_validation', [function() {
    WDN.jQuery('#add_media').validation({
        containerClassName: 'validation-container',
        immediate: true
    });
}]);
</script>


<script type="text/javascript">
    // Custom example logic

    var max_file_count = 1;
    var uploader = new plupload.Uploader({
        runtimes : 'html5,flash,silverlight,html4',
        browse_button : 'mh_upload_media', // you can pass in id...
        container: document.getElementById('mh_upload_media_container'), // ... or DOM Element itself
        url : '<?php echo UNL_MediaHub_Controller::getURL()?>manager/?view=upload&format=json',
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
                {title : "Video files", extensions : "mp4,3gp"},
                {title : "Audio files", extensions : "mp3"}
            ]
        },

        init: {
            PostInit: function() {
                WDN.jQuery('#filelist').text('').hide();
                var input = WDN.jQuery('#mh_upload_media_container').find('input').each(function() {
                    var label = WDN.jQuery('<label/>', {
                        'for': this.id,
                        'class': 'wdn-text-hidden',
                        'text': 'Browse for media'
                    });
                    WDN.jQuery(this).before(label);
                });
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

            FileUploaded: function(up, file, info) {
                // Called when file has finished uploading
                var response = WDN.jQuery.parseJSON(info.response);
                if (typeof response.result === 'undefined') {
                    //Bad response, fail for now.
                    return;
                }
                
                if (response.result !== 'complete') {
                    //did not complete
                    return;
                }
                
                WDN.jQuery('#media_url').attr('value', response.url);
                WDN.jQuery('#publish').removeAttr('disabled');
                
            },

            Error: function(up, err) {
                alert("Error #" + err.code + ": " + err.message);
            }
        }
    });

    uploader.init();

</script>