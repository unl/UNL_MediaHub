// Custom example logic

require(['jquery'], function($){
    $(document).ready(function(){

        var max_file_count = 1;
        var submit_text = 'Save';
        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'mh_upload_media', // you can pass in id...
            container: document.getElementById('mh_upload_media_container'), // ... or DOM Element itself
            url : baseurl+'?view=upload&format=json',
            flash_swf_url : baseurl+'templates/html/scripts/plupload/Moxie.swf',
            silverlight_xap_url : baseurl+'templates/html/scripts/plupload/Moxie.xap',
            drop_element: "mh_upload_media",
            chunk_size: '5mb',
            max_retries: 10,
            multi_selection:false,
            multi_selection:false,
            multipart_params: {
                __unlmy_posttarget: 'upload_media',
                csrf_name: document.querySelector('input[name="csrf_name"]').value,
                csrf_value: document.querySelector('input[name="csrf_value"]').value
            },
            filters : {
                max_file_size : MAX_UPLOAD+'mb',
                mime_types: [
                    {title : "Video files", extensions : VALID_VIDEO_EXTNESIONS},
                    {title : "Audio files", extensions : "mp3"}
                ]
            },

            init: {
                PostInit: function() {
                    $('#filelist').text('').hide();
                    var input = $('#mh_upload_media_container').find('input').each(function() {
                        var label = $('<label/>', {
                            'for': this.id,
                            'class': 'dcf-sr-only',
                            'text': 'Browse for media'
                        });
                        $(this).before(label);
                    });
                },

                FilesAdded: function(up, files) {
                    plupload.each(files, function(file) {
                        if (up.files.length > max_file_count) {
                            up.removeFile(file);
                            return;
                        }

                        document.getElementById('filelist').innerHTML += '<div id="' + file.id + '" class="dcf-w-max-100% dcf-word-wrap" style="hyphens:none;">' + file.name + '<br> (' + plupload.formatSize(file.size) + ') <b></b></div>';
                    });
                    uploader.disableBrowse();
                    var $submit = $('input[type=submit]');
                    submit_text = $submit.attr("value");
                    $submit.attr("disabled", true).attr("value", "Upload in Progress...");
                    $('#mh_upload_media').hide();
                    $('#filelist').show();
                    uploader.start();
                },

                UploadProgress: function(up, file) {
                    document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                },

                FileUploaded: function(up, file, info) {
                    // Called when file has finished uploading
                    var response = $.parseJSON(info.response);
                    if (typeof response.result === 'undefined') {
                        //Bad response, fail for now.
                        return;
                    }

                    if (response.result !== 'complete') {
                        //did not complete
                        return;
                    }

                    if(response.projection == "equirectangular"){
                        $('input[name=projection]').prop('checked', true);
                        $("#autodetect-360").addClass("open").append($("<div>").text("We noticed you uploaded a 360 video. If the video you uploaded isn't intended to be viewed with our 360 player please uncheck the box marked '360 Video'"))
                        
                        // alert("We noticed you uploaded a 360 video. If the video you uploaded isn't intended to be viewed with our 360 player please uncheck the box marked '360 Video'");
                    }

                    $('#media_url').attr('value', response.url);
                    $('#publish').removeAttr('disabled');
                    $('input[type=submit]').removeAttr("disabled");
                    $('input[type=submit]').attr("value", submit_text);

                },

                Error: function(up, err) {
                    alert("Error #" + err.code + ": " + err.message);
                }
            }
        });
        
        uploader.init();
    });
});
