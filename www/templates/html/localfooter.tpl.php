
<div class="wdn-grid-set wdn-footer-links-local">
    <div class="bp960-wdn-col-two-thirds">
        <div class="wdn-footer-module">
            <span role="heading" class="wdn-footer-heading">About UNL MediaHub</span>
            <?php
            if ($file = @file_get_contents(UNL_MediaHub::getRootDir() . '/tmp/iim-app-footer.html')) {
                echo $file;
            } else {
                echo file_get_contents('http://iim.unl.edu/iim-app-footer?format=partial');
            }
            ?>
        </div>
    </div>
    <div class="bp960-wdn-col-one-third">
        <div class="wdn-footer-module">
            <span role="heading" class="wdn-footer-heading">Related Links</span>
            <ul class="wdn-related-links-v1">
                <li><a href="<?php echo UNL_MediaHub_Controller::getURL() ?>developers">Developer Documentation</a></li>
                <li><a href="<?php echo UNL_MediaHub_Controller::getURL() ?>help/media-prep">Preparing Your Media</a></li>
                <li><a href="http://itunes.unl.edu/">UNL On iTunes U</a></li>
                <li><a href="http://iim.unl.edu/">Internet and Interactive Media</a></li>
            </ul>
        </div>
    </div>
</div>

<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-22295578-1']);
    _gaq.push(['_setDomainName', '.unl.edu']);
    _gaq.push(['_setAllowLinker', true]);
    _gaq.push(['_trackPageview']);
</script>
