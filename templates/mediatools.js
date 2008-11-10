
function writeUNLPlayer(mediaurl, id)
{
    var image = UNL_MediaYak_thumbnail_generator+mediaurl;    
    
	var flashvars = {
	  file: mediaurl,
	  image: image,
	  volume: "100",
	  autostart: "true"
	};
	var params = {
	  allowfullscreen: "true",
	  allowscriptaccess: "always"
	};
	var attributes = {
	  id: "myDynamicContent",
	  name: "myDynamicContent"
	};
	
	swfobject.embedSWF('/ucomm/templatedependents/templatesharedcode/scripts/components/mediaplayer/player.swf',
                       id, "450", "358", "9.0.0",
                       "/ucomm/templatedependents/templatesharedcode/scripts/components/mediaplayer/expressInstall.swf",
                       flashvars, params, attributes);

    
}