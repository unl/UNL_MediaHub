<?php
$data = array();
$data['markup'] = $savvy->render($context->media, 'MediaPlayer.tpl.php');
$data['id'] = $context->media->id;
?>
var data = <?php echo json_encode($data);?>;

(function () {
    var j, l, t, i, r = function () {
        WDN.loadJQuery(function() {
            WDN.jQuery('#mediahub_embed_' + data.id).html(data.markup);
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
