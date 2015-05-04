<?php
$data = array();
$data['markup'] = $savvy->render($controller->getMediaPlayer($context->media));
$data['id'] = $context->media->id;
?>

(function () {
    var j, l, t, i, r = function () {
        WDN.loadJQuery(function() {
            var data = <?php echo json_encode($data);?>;
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
