// Custom example logic

WDN.jQuery(document).ready(function(){

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
            WDN.jQuery('input[type=submit]').attr("disabled", true);
            submit_text = WDN.jQuery('input[type=submit]').attr("value");
            WDN.jQuery('input[type=submit]').attr("value", "Upload in Progress...");
            WDN.jQuery('#mh_upload_media').hide();
            WDN.jQuery('#filelist').show();
            console.log("rawr")
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
            WDN.jQuery('input[type=submit]').removeAttr("disabled");
            WDN.jQuery('input[type=submit]').attr("value", submit_text);
            
        },

        Error: function(up, err) {
            alert("Error #" + err.code + ": " + err.message);
        }
    }
});

uploader.init();

});
