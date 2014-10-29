<?php
$page->addScript(UNL_MediaHub_Controller::getURL() . 'templates/html/scripts/plupload/plupload.full.min.js');
?>

<div class="wdn-band wdn-light-neutral-band mh-upload-band">
    <div class="wdn-inner-wrapper">
        <h1 class="wdn-brand">Manage Media</h1>
        <form action="?" method="post">
            <div class="wdn-grid-set">
                <div id="mh_upload_media_container" class="bp2-wdn-col-three-sevenths">
                    <div id="mh_upload_media" class="mh-upload-box wdn-center">
                        <h2>+<span class="wdn-subhead">Add Media</span></h2>
                        <p>.mp4, .mov, .mp3, .wav, .aac</p>
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
                                    <textarea rows="4" type="text" id="description" name="description">Explain what this
                                        media is all about. Use a few sentences, but keep it to 1 paragraph.
                                    </textarea>
                                    <input type="submit" name="publish" value="Publish">
                                </li>
                            </ol>
                        </div>
                        <div class="bp1-wdn-col-two-fifths">
                            <ol>
                                <li class="clear-top">
                                    <label for="privacy">Privacy</label>
                                    <div class="wdn-icon-info mh-tool-tip">
                                        <div>
                                            <ul>
                                                <li><span class="heading">Public</span> - Anyone can access the media.
                                                </li>
                                                <li><span class="heading">Unlisted</span> - Media will not be included
                                                    in public MediaHub listings.
                                                </li>
                                                <li><span class="heading">Private</span> - Only members of channels that
                                                    the media is included in can access it.
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <select name="privacy" id="privacy">
                                        <option value="Public" selected="selected">Public</option>
                                        <option value="unlisted">Unlisted</option>
                                        <option value="Privacy">Private</option>
                                    </select>
                                </li>
                                <li>
                                    <label for="channels">Channels
                                        <span class="required">*</span>
                                    </label>
                                    <div class="mh-channel-box">
                                        <ul>
                                            <li>
                                                <input type="checkbox" id="channels_1" name="channels" value="Bike">
                                                <label for="channels_1">This is a channelname</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" id="channels_2" name="channels" value="Car">
                                                <label for="channels_2">Peanut butter biscuits</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" id="channels_3" name="channels" value="Car">
                                                <label for="channels_3">Spencer's Channel</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" id="channels_4" name="channels" value="Car">
                                                <label for="channels_4">Okay then</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" id="channels_5" name="channels" value="Car">
                                                <label for="channels_5">Peanut butter</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" id="channels_6" name="channels" value="Car">
                                                <label for="channels_6">WOO butter</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" id="channels_7" name="channels" value="Car">
                                                <label for="channels_7">Peanut gutter</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" id="channels_8" name="channels" value="Car">
                                                <label for="channels_8">Okay then</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" id="channels_9" name="channels" value="Car">
                                                <label for="channels_9">Peanut butter</label>
                                            </li>
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
                {title : "Video files", extensions : "mp4,mov,mp3"},
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

            Error: function(up, err) {
                alert("Error #" + err.code + ": " + err.message);
            }
        }
    });

    uploader.init();

</script>