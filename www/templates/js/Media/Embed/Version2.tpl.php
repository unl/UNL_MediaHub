<?php
$data = array();
$data['markup'] = $savvy->render($controller->getMediaPlayer($context->media));
$data['id'] = $context->media->id;
?>

(function () {
    var r = function () {
        WDN.loadJQuery(function() {
            var data = <?php echo json_encode($data);?>;
            WDN.jQuery('#mediahub_embed_' + data.id).html(data.markup);
        });
    };
    
    if (typeof(WDN) === 'undefined') {
        //This isn't the WDN framework, must load a minimum version of it
        var base_url = '//unlcms.unl.edu/wdn/templates_4.0/scripts/';

        //Scripts that are required to load before running embed code
        var syncLoad = [
            "require.js",
            "modernizr-wdn.js",
            "ga.js",
            "wdn.js"
        ];

        var loadMediaHubJS = function(src) {
            var script = document.createElement('script'),
                loaded;
            script.setAttribute('src', base_url+syncLoad[src]);
            script.onreadystatechange = script.onload = function() {
                if (!loaded) {
                    if (src < syncLoad.length-1) {
                        loadMediaHubJS(src+1);
                    } else {
                        //This is the last script, run requirejs configuration
                        requirejs.config({
                            baseUrl: base_url,
                            map: {
                                "*": {
                                    css: 'require-css/css'
                                }
                            }
                        });

                        //We are finally ready; Load embed code
                        r();
                    }
                }
                loaded = true;
            };
            document.getElementsByTagName('head')[0].appendChild(script);
        };

        //Start loading everything
        loadMediaHubJS(0);
    } else {
        //We are in the framework, just run the embed code
        r();
    }
})();
