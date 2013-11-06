(function () {
    var j, l, t, r = function () {
        <?php if ($context->media) { ?>
        WDN.setPluginParam('mediaelement_wdn', 'options', {
            success: function (m) {
                var w = false, u = '<?php echo $controller->getURL($context->media) ?>';
                m.addEventListener('play', function () {
                    if (!w) {
                        WDN.jQuery.post(u, {action: "playcount"});
                        w = true;
                    }
                });
            }
        });
        <?php } ?>
        WDN.initializePlugin('mediaelement_wdn');
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
