function toggleSingleCaptionTrack(options) {
    options = (typeof options !== 'undefined') ?  options : {};
    options.activeColor = (typeof options.activeColor !== 'undefined') ?  options.activeColor : "#999";
    this.on("loadedmetadata", function(){
        var textTracks = this.textTracks();
        var mq = window.matchMedia( "(min-width: 1024px)" );
        var Button = videojs.getComponent("subsCapsButton");
        var activeTextTrackCount = 0;
        var activeTextTrack;
        
        // var ToggleCaptionsButton = videojs.extend(Button, {
          
        // });

        class ToggleCaptionsButton extends Button {
            constructor(player, options) {
                Button.call(this, player, options);
                Button.apply(this, arguments);
                
                var styleEl = document.createElement('style'),
                    styleSheet;
                document.head.appendChild(styleEl);
                styleSheet = styleEl.sheet;
                styleSheet.insertRule(".vjs-subs-caps-button.toggle.active .vjs-icon-placeholder:before { color: "+options.activeColor+" }", 0);
            }
            /**
             * Put the current `MenuButton` into a pressed state.
             */
            pressButton() {
                if (mq.matches) {
                    //We are in desktop. Make sure the caption settings button is enabled and perform default functionality
                    this.menu.children()[0].enable();
                    Button.prototype.pressButton.call(this);
                } else {
                    //We are on mobile (or at least at a mobile width)
                    if (activeTextTrackCount === 1 && activeTextTrack) {
                        //Only one caption track was found. Just toggle it
                        if(activeTextTrack.mode === "showing") {
                            activeTextTrack.mode = "disabled";
                        } else {
                            activeTextTrack.mode = "showing";
                        }
                        CheckCaptionsShowing(activeTextTrack, MyToggleCaptionsButton);
                    } else {
                        //More than one caption track was found, so we need to display a list
                        //Disable the caption settings menu item because it doesn't work on mobile
                        this.menu.children()[0].disable();
                        //Focus the second option
                        this.menu.focus(1);
                    }
                }
            }
        }

        this.controlBar.removeChild("subsCapsButton");
        videojs.registerComponent('ToggleCaptionsButton', ToggleCaptionsButton);
        var ButtonChildPosition = this.controlBar.children().indexOf(this.controlBar.getChild("remainingTimeDisplay"))+1; // place after remainingTimeDisplay
        var MyToggleCaptionsButton = this.controlBar.addChild("ToggleCaptionsButton", {activeColor: options.activeColor}, ButtonChildPosition);

        textTracks.on("change", function(){
            handleTextTrackChange();
        });
        
        function handleTextTrackChange() {
            activeTextTrackCount = 0;
            for(var i = 0; i < textTracks.length; ++i){
                if(textTracks[i].kind === 'captions') {
                    activeTextTrackCount++;
                    activeTextTrack = textTracks[i];
                }
            }
            if (activeTextTrackCount === 1 && !mq.matches) {
                //Indicate that this should be a toggle button in mobile
                MyToggleCaptionsButton.addClass('toggle');
            } else {
                MyToggleCaptionsButton.removeClass('toggle');
            }

            CheckCaptionsShowing(activeTextTrack, MyToggleCaptionsButton);
        }
        
        //Initialize text track change
        handleTextTrackChange();
    });
};

function CheckCaptionsShowing(textTrack, button){
  if (!textTrack) {
      return;
  }
  
  if(textTrack.mode === "showing"){
    button.addClass("active");
  }else{
    button.removeClass("active");
  }
}

videojs.registerPlugin('toggleSingleCaptionTrack', toggleSingleCaptionTrack);