<?php
$upload_id = md5(microtime() . rand());

function return_bytes($val)
{
    //This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)
    $l = substr($val, -1);
    $ret = substr($val, 0, -1);
    switch(strtoupper($l)){
        case 'P':
            $ret *= 1024;
        case 'T':
            $ret *= 1024;
        case 'G':
            $ret *= 1024;
        case 'M':
            $ret *= 1024;
        case 'K':
            $ret *= 1024;
            break;
    }
    return $ret;
}

?>
<script type="text/javascript">
var upload = function() {
    
    /* private variables */
    
    var uploadprogress = null;
    
    var startTime = null;
    var upload_max_filesize = <?php echo return_bytes(ini_get('upload_max_filesize'));?>;
    
    var infoUpdated = 0;
    
    var writeStatus = function(text,color) {
        var statDiv = document.getElementById("uploadstatus");
        if (color == 1 ) {
            statDiv.style.backgroundColor = "green";
        } else if (color == 2 ) {
            statDiv.style.backgroundColor = "orange";
        } else if (color == 3 ) {
            statDiv.style.backgroundColor = "red";
        } else {
            statDiv.style.backgroundColor = "white";
        }
        statDiv.innerHTML = text;
    }

    return {
        start: function() {
           uploadprogress = document.getElementById("uploadprogress");
           startTime = new Date();
           infoUpdated = 0;
           this.requestInfo();
        },
        stop: function(url) {
           if (typeof url == 'undefined' || url) {
                var secs = (new Date() - startTime)/1000;
                var statusText = "Upload succeeded, it took " + secs + " seconds. <br/> ";
                if (infoUpdated > 0) {
                    writeStatus(statusText + "You had " + infoUpdated + " updates from the progress meter, looks like it's working fine",1);
                } else {
                    statusText += "BUT there were no progress meter updates<br/> ";
                    if (secs < 3) {
                      writeStatus(statusText + "Your upload was maybe too short, try with a bigger file or a slower connection",2);
                    } else {
                      writeStatus(statusText + "Your upload should have taken long enough to have an progress update. Maybe it really does not work...",3);
                    }
                }
                mediaDetails.getPreview(url); 
           } else {
               writeStatus('PHP did not report any uploaded file, maybe it was too large, try a smaller one (post_max_size: <?php echo ini_get('post_max_size');?>)',3);
           }
           startTime = null;
        },
        requestInfo: function() {
            uploadprogress.src="?view=uploadprogress&format=barebones&id=<?php echo $upload_id; ?>&"+new Date();
        },
        
        updateInfo: function(uploaded, total, estimatedSeconds) {
            if (startTime) {
                if (uploaded) {
                    infoUpdated++;
                    if (total > upload_max_filesize) {
                        writeStatus("The file is too large and won't be available for PHP after the upload<br/> Your file size is " + total + " bytes. Allowed is " + upload_max_filesize + " bytes. That's " + Math.round (total / upload_max_filesize * 100) + "% too large<br/> Download started since " + (new Date() - startTime)/1000 + " seconds. " + Math.floor(uploaded / total * 100) + "% done, " + estimatedSeconds + "  seconds to go",2);
                    } else {
                        writeStatus("Download started since " + (new Date() - startTime)/1000 + " seconds. " + Math.floor(uploaded / total * 100) + "% done, " + estimatedSeconds + "  seconds to go");
                    }
                } else {
                    writeStatus("Download started since " + (new Date() - startTime)/1000 + " seconds. No progress info yet");
                }
                window.setTimeout("upload.requestInfo()", 1000);
            }
        }
    }
}()
</script>
<form id="fileUpload" onsubmit="upload.start()" target="uploadtarget" action="?" enctype="multipart/form-data" method="post" class="zenform cool">
    <input type="hidden" name="UPLOAD_IDENTIFIER" value="<?php echo $upload_id;?>" />
    <input type="hidden" name="__unlmy_posttarget" value="upload_media" />
    <fieldset id="addMedia">
    <legend>Add New Media</legend>
        <ol>
            <li>
                <label><span class="required">*</span>URL of Media File <span class="helper">Media types supported: .m4v, .mp4, .mp3</span></label>
                <input id="url" name="url" type="text" value="<?php echo htmlentities(@$context->media->url, ENT_QUOTES); ?>" />
                <?php
                $max_upload_size = min(return_bytes(ini_get('post_max_size')), return_bytes(ini_get('upload_max_filesize')));
                ?>
                <label>Or, Upload Media <span class="helper"><?php echo "Maximum upload file size is ".($max_upload_size/(1024*1024))."MB." ?></span></label>
                <input id="file_upload" name="file_upload" type="file" />
            </li>
        </ol>
        <input type="submit" name="submit" id="mediaSubmit" value="Add Media" />
    </fieldset>
</form>
<div id="uploadstatus"></div>
<iframe name="uploadprogress" width="200px" height="50px" id="uploadprogress"></iframe>
<iframe name="uploadtarget" width="200px" height="50px" id="uploadtarget"></iframe>