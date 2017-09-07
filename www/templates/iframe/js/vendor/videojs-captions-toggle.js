function toggleSingleCaptionTrack(options) {
    options = (typeof options !== 'undefined') ?  options : {};
    options.activeColor = (typeof options.activeColor !== 'undefined') ?  options.activeColor : "#999";
    this.on("loadedmetadata", function(){
      var textTracks = this.textTracks();
      if(textTracks.length === 1){
        var Button = videojs.getComponent("Button");
        var ToggleCaptionsButton = videojs.extend(Button, {
          constructor: function(player, options) {
            Button.apply(this, arguments);
            this.addClass("vjs-captions-button")
            this.controlText("Toggle Captions")

            if(options.activeColor){
              var styleEl = document.createElement('style'),
              styleSheet;
              document.head.appendChild(styleEl);
              styleSheet = styleEl.sheet;
              styleSheet.insertRule(".vjs-captions-button.active:before { color: "+options.activeColor+" }", 0);
            }

            
          },
          handleClick: function(){
            if(textTracks[0].mode == "showing"){
              textTracks[0].mode = "disabled";
            }else{
              textTracks[0].mode = "showing";
            }
            CheckCaptionsShowing(textTracks[0], MyToggleCaptionsButton);
          }, 
        });
        this.controlBar.removeChild("captionsButton")
        videojs.registerComponent('ToggleCaptionsButton', ToggleCaptionsButton);
        var ButtonChildPosition = this.controlBar.children().indexOf(this.controlBar.getChild("remainingTimeDisplay"))+1 // place after remainingTimeDisplay
        var MyToggleCaptionsButton = this.controlBar.addChild("ToggleCaptionsButton", {activeColor: options.activeColor}, ButtonChildPosition);

        textTracks.on("change", function(){
          CheckCaptionsShowing(textTracks[0], MyToggleCaptionsButton);
        });
        CheckCaptionsShowing(textTracks[0], MyToggleCaptionsButton);

      } else if(textTracks.length > 1) {
        var styleEl = document.createElement('style'),
        styleSheet;
        document.head.appendChild(styleEl);
        styleSheet = styleEl.sheet;
        styleSheet.insertRule(".vjs-texttrack-settings { display: none; }", 0);
      }
    });
};

function CheckCaptionsShowing(textTrack, button){
  if(textTrack.mode == "showing"){
    button.addClass("active")
  }else{
    button.removeClass("active")
  }
}

videojs.plugin('toggleSingleCaptionTrack', toggleSingleCaptionTrack);