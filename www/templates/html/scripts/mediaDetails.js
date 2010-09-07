var jQuery = WDN.jQuery;
WDN.jQuery(document).ready(function() {
    if (formView == 'edit'){
    	WDN.jQuery("#addMedia").hide();
        WDN.jQuery("#feedlist").hide();
        WDN.jQuery(".headline_main, #formDetails, #formDetails form, #formDetails fieldset, #continue3").not("#addMedia").css({"display" : "block"});
        WDN.jQuery("#formDetails").removeClass("two_col right");
    }
    WDN.jQuery("#continue2").click(function() {
            unl_check = /^http:\/\/([^\/]+)\.unl\.edu\/(.*)/;
            var r = unl_check.exec(document.getElementById("url").value);
            if (r == null) {
                alert('Sorry, you must use a .unl.edu URL!');
                return false;
            }
            //WDN.jQuery("").hide();
            WDN.jQuery("#addMedia, #feedlist").slideUp(400, function() {
                WDN.jQuery(".headline_main").slideDown(400, function() {
                    WDN.jQuery("#maincontent form.zenform").css({"width" : "930px"}).parent("#formDetails").removeClass("two_col right");
                    WDN.jQuery("#existing_media, #enhanced_header, #feedSelect, #maincontent form.zenform #continue3").slideDown(400);
                    WDN.jQuery("#media_url").attr("value", WDN.jQuery("#url").val());
                });
            });
            document.getElementById("thumbnail").src = "http://itunes.unl.edu/thumbnails.php?url="+document.getElementById("url").value;
            return false;
        }
    );
    WDN.jQuery("#itunes_header ol").hide();
    WDN.jQuery("#mrss_header ol").hide();
    
    WDN.jQuery("#itunes_header legend").click(function() {
      WDN.jQuery("#itunes_header ol").toggle(400);
      return false;
    });
    WDN.jQuery("#mrss_header legend").click(function() {
      WDN.jQuery("#mrss_header ol").toggle(400);
      return false;
    });
    

});
WDN.loadJS("templates/scripts/tiny_mce/jquery.tinymce.js", function() {
    WDN.jQuery("textarea#description").tinymce({
            // Location of TinyMCE script
            script_url : "templates/scripts/tiny_mce/tiny_mce.js",
            theme : "advanced",
            skin : "unl",
            
            // Theme options
	        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,|,bullist,numlist,|,link,unlink,anchor,|,removeformat,cleanup,help,code,styleselect,formatselect",
	        theme_advanced_buttons2 : "",
	        theme_advanced_buttons3 : "",
	        theme_advanced_toolbar_location : "top",
	        theme_advanced_toolbar_align : "left",
	        theme_advanced_statusbar_location : "bottom",
	        theme_advanced_resizing : true,
            theme_advanced_row_height : 33
    });
});
