<?php
$upload_id = md5(microtime() . rand());

function return_bytes($val)
{
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    switch($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return $val;
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
        var statDiv = document.getElementById("status");
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
           ifr = document.getElementById("uploadprogress");
           startTime = new Date();
           infoUpdated = 0;
           this.requestInfo();
        },
        stop: function(files) {
           if (typeof files == 'undefined' || files) {
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
           } else {
               writeStatus('PHP did not report any uploaded file, maybe it was too large, try a smaller one (post_max_size: <?php echo ini_get('post_max_size');?>)',3);
           }
           startTime = null;
        },
        requestInfo: function() {
                uploadprogress.src="?view=uploadprogress&id=<?php echo $upload_id; ?>&"+new Date();
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
<form onsubmit="upload.start()" target="uploadtarget" action="?" enctype="multipart/form-data" method="post">
    <input type="hidden" name="UPLOAD_IDENTIFIER" value="<?php echo $upload_id;?>" />
    <input type="hidden" name="__unlmy_posttarget" value="upload_media" />
    <label>Select File:</label>
    <input type="file" name="file_upload" />
    <label>Upload File:</label>
    <input type="submit" value="Upload File" />
</form>
<iframe name="uploadprogress" width="200px" height="100px" id="uploadprogress"></iframe>
<iframe name="uploadtarget" width="200px" height="50px" id="uploadtarget"></iframe>