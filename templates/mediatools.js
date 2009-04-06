
var UNL_MediaYak = function() {
	var player = false;
	
	var videoName;
	var videoURL;
	var endTime;
	var startTime;
	var url;
	var thumbnailGenerator;
	return {
		getURL : function() {
	        return UNL_MediaYak.url;
	    },
		writePlayer : function(title, mediaurl, id, width, height) {
	    	UNL_MediaYak.videoURL = mediaurl;
	    	UNL_MediaYak.videoName = title;
			if (mediaurl.substr(mediaurl.length-3,3) == 'mp3') {
			   // embed audio
				UNL_MediaYak.writeAudioPlayer(mediaurl, id);
			} else {
			   // embed video
				UNL_MediaYak.writeVideoPlayer(mediaurl, id, width, height);
			}
		},
		writeVideoPlayer: function(mediaurl, id, width, height)
		{
		    var image = UNL_MediaYak_thumbnail_generator+mediaurl;    
		    
		    var flashvars = {
		      file: mediaurl,
		      image: image,
		      volume: "100",
		      autostart: "true",
		      skin: '/ucomm/templatedependents/templatesharedcode/scripts/components/mediaplayer/UNLVideoSkin.swf',
		    };
		    
		    var params = {
		      allowfullscreen: "true",
		      allowscriptaccess: "always",
		      id: id,
		      name: id
		    };
		    
		    swfobject.embedSWF('/ucomm/templatedependents/templatesharedcode/scripts/components/mediaplayer/player.swf',
		               id, width, height, "9.0.0",
		               "/ucomm/templatedependents/templatesharedcode/scripts/components/mediaplayer/expressInstall.swf",
		               flashvars, params);

		},
		writeAudioPlayer: function(mediaurl, id)
		{
		    var flashvars = {
		      soundFile: mediaurl,
		      autostart: "yes"
		    };
		    
		    swfobject.embedSWF(UNL_MediaYak.getURL()+'templates/audio-player/player.swf',
		               id, "450", "358", "9.0.0",
		               "/ucomm/templatedependents/templatesharedcode/scripts/components/mediaplayer/expressInstall.swf",
		               flashvars);
		},
		addListeners : function() {
			if (UNL_MediaYak.player) { 
				UNL_MediaYak.player.addModelListener('TIME', 'UNL_MediaYak.timeListener');
//				UNL_MediaYak.player.addModelListener('LOADED', "UNL_MediaYak.loadedListener");
				UNL_MediaYak.player.addModelListener('STATE', "UNL_MediaYak.stateListener");
				UNL_MediaYak.player.addModelListener('ERROR', "UNL_MediaYak.errorListener");
				
				UNL_MediaYak.player.addControllerListener('RESIZE',"UNL_MediaYak.resizeListener");
				UNL_MediaYak.player.addControllerListener('SEEK',"UNL_MediaYak.seekListener");
			} else {
				setTimeout("UNL_MediaYak.addListeners()", 100);
			}
		},
		timeListener : function(obj) {
			var currentPosition = obj.position;
			var currentDuration = obj.duration;
		},
		loadedListener : function(obj) {
			if (obj.loaded < obj.total) {
				//start the timer
//				recordStartTime();
			}
			if (obj.total == obj.loaded && UNL_MediaYak.endTime == 0) { 
				//report the timer value and clear it out.
//				recordEndTime();
			}
		},
		stateListener: function(obj) {
			//IDLE, BUFFERING, PLAYING, PAUSED, COMPLETED
			var currentState = obj.newstate;
			var previousState = obj.oldstate;
			if (currentState != previousState) { 
				UNL_MediaYak.trackEvent(currentState);
			}
		},
		errorListener: function(obj) {
			//Check for errors
			var currentError = obj.message;
			UNL_MediaYak.trackEvent(currentError);
		},
		resizeListener: function(obj) {
			//Check for fullsceen
			var currentStatus = obj.fullscreen; 

			if (currentStatus == true) {
				currentStatus = "FULLSCREEN OPENED";
			} else {
				currentStatus = "FULLSCREEN CLOSED";
			};
			UNL_MediaYak.trackEvent(currentStatus);
		},
		seekListener: function(obj) {
			//check if the seek button has been clicked
			var newPosition = obj.position;
			var currentSeek = "SEEKING";
			UNL_MediaYak.trackEvent(currentSeek);
		},
		recordStartTime: function() {
			//start time of video load
			UNL_MediaYak.startTime = parseFloat((new Date()).getTime());
		},
		recordEndTime: function() {
			//end time of video load
			UNL_MediaYak.endTime = parseFloat((new Date()).getTime());
			var timeDiff = (UNL_MediaYak.endTime - UNL_MediaYak.startTime)/100;
			UNL_MediaYak.trackEvent("LOAD TIME", timeDiff);
		},
		trackEvent: function(event, param1) {
			var video_identifier = UNL_MediaYak.videoName + ' ' + UNL_MediaYak.videoURL;
			try {
				//the function to fire the GA Tracking
				// category: video
				// action: action
				// label: video name
				// value: numeric (time)
				
				if (param1 == undefined) {
					console.log('Video: %s, Event: %s', video_identifier, event);
					wdnTracker._trackEvent('Videos', event, video_identifier);
				} else {
					console.log('Video: %s, Event: %s, Value: %s', video_identifier, event, param1);
					wdnTracker._trackEvent('Videos', event, video_identifier, param1);
				}
			} catch (e) {}
		}
	};
}();

function playerReady(thePlayer) {
	//start the player and JS API
	UNL_MediaYak.player = document.getElementById(thePlayer.id);
	UNL_MediaYak.addListeners();
}