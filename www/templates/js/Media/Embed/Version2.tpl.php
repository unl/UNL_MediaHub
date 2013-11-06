<?php
$markup = $savvy->render($context->media, 'MediaPlayer.tpl.php');

//Add slashes so that it is javascirpt safe
$markup = addslashes($markup);

//Replace newlines with spaces
$markup = trim(preg_replace('/\s+/', ' ', $markup));
?>
var markup = "<?php echo $markup?>";

(function () {
    var j, l, t, i, r = function () {
        WDN.loadJQuery(function() {
            WDN.jQuery('#mediahub_embed_<?php echo $context->media->id?>').html(markup);
        });
    };
    if (typeof(WDN) === 'undefined') {
        t = '//www.unl.edu/';
        l = function () {
            l = function () {
            };
            WDN.template_path = t;
            r();
        };
        j = document.createElement('script');
        j.type = 'text/javascript';
        j.src = t + 'wdn/templates_3.1/scripts/wdn.js';
        j.onreadystatechange = function () {
            if (j.readyState == 'loaded' || j.readyState == 'complete') {
                l();
            }
        };
        j.onload = l;
        document.getElementsByTagName("head")[0].appendChild(j);
    } else {
        r();
    }
})();
