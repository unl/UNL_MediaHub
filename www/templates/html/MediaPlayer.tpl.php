<?php
if ($context->isVideo()) {
    echo $savvy->render($context, 'MediaPlayer/Video.tpl.php');
} else {
    echo $savvy->render($context, 'MediaPlayer/Audio.tpl.php');
}
?>

<script type="text/javascript">
    (function () {
        var i, e = function () {
            <?php if ($context->id) { ?>
            i = <?php echo $context->id ?>;
            WDN.setPluginParam('mediaelement_wdn', 'options', {
                success: function (m) {
                    var w = false, u = '<?php echo $controller->getURL($context) ?>';
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
        e();
    })();
</script>
