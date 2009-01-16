
function writeUNLPlayer(mediaurl, id)
{
    
	
	if (mediaurl.substr(mediaurl.length-3,3) == 'mp3') {
	   // embed audio
	   writeUNLAudioPlayer(mediaurl, id);
	} else {
	   // embed video
	   writeUNLVideoPlayer(mediaurl, id);
	}
    
}

function writeUNLVideoPlayer(mediaurl, id)
{
    var image = UNL_MediaYak_thumbnail_generator+mediaurl;    
    
    var flashvars = {
      file: mediaurl,
      image: image,
      volume: "100",
      autostart: "true",
      plugins: "googlytics-1"
    };
    var params = {
      allowfullscreen: "true",
      allowscriptaccess: "always"
    };
    
    swfobject.embedSWF('/ucomm/templatedependents/templatesharedcode/scripts/components/mediaplayer/player.swf',
               id, "450", "358", "9.0.0",
               "/ucomm/templatedependents/templatesharedcode/scripts/components/mediaplayer/expressInstall.swf",
               flashvars, params);

}

function writeUNLAudioPlayer(mediaurl, id)
{
    var flashvars = {
      soundFile: mediaurl,
      autostart: "yes"
    };
    
    swfobject.embedSWF(UNL_MediaYak.getURL()+'templates/audio-player/player.swf',
               id, "450", "358", "9.0.0",
               "/ucomm/templatedependents/templatesharedcode/scripts/components/mediaplayer/expressInstall.swf",
               flashvars);
}