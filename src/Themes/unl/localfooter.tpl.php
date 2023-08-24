<nav id="dcf-footer-group-1" role="navigation" aria-labelledby="dcf-footer-group-1-heading">
    <h3 class="dcf-txt-md dcf-bold dcf-uppercase dcf-lh-3 unl-ls-2 unl-cream" id="dcf-footer-group-1-heading">About UNL MediaHub</h3>
    <p>
        This application is a product of the <a href="https://dxg.unl.edu/">Digital
        Experience Group</a> at <a href="https://www.unl.edu/">Nebraska</a>. DXG
        is a part of <a href="https://ucomm.unl.edu/">University Communication &amp; Marketing</a>.
    </p>
</nav>
<nav id="dcf-footer-group-2" role="navigation" aria-labelledby="dcf-footer-group-2-heading">
    <h3 class="dcf-txt-md dcf-bold dcf-uppercase dcf-lh-3 unl-ls-2 unl-cream" id="dcf-footer-group-2-heading">Related Links</h3>
    <ul class="dcf-list-bare dcf-mb-0">
        <li><a href="<?php echo UNL_MediaHub_Controller::getURL() ?>developers">Developer Documentation</a></li>
        <li><a href="<?php echo UNL_MediaHub_Controller::getURL() ?>help/media-prep">Preparing Your Media</a></li>
        <li><a href="https://wdn.unl.edu/documentation/unl-mediahub">Documentation</a></li>
        <li><a href="https://wdn.unl.edu/documentation/unl-mediahub/changelog">Changelog</a></li>
        <li><a href="https://dxg.unl.edu/">Digital Experience Group</a></li>
    </ul>
</nav>

<?php
$page->addScriptDeclaration("var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-22295578-1']);
    _gaq.push(['_setDomainName', '.unl.edu']);
    _gaq.push(['_setAllowLinker', true]);
    _gaq.push(['_trackPageview']);");
?>
