<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <!-- InstanceBeginEditable name="doctitle" -->
    <title>Sample Custom DCF Theme</title>
    <!-- InstanceEndEditable -->
    <link rel="preload" href="http://iimjsturek.unl.edu/dcf/example/fonts/lato-subset-dcf.woff2" as="font" type="font/woff2" crossorigin="">
    <style>
        @font-face {
            font-family: LatoSubset;
            src: url('fonts/lato-subset-dcf.woff2') format('woff2'),
            url('fonts/lato-subset-dcf.woff') format('woff');
        }
        @font-face {
            font-family: Lato;
            src: url('fonts/lato-regular-dcf.woff2') format('woff2'),
            url('fonts/lato-regular-dcf.woff') format('woff');
            font-display: swap;
        }
        @font-face {
            font-family: Lato;
            src: url('fonts/lato-bold-dcf.woff2') format('woff2'),
            url('fonts/lato-bold-dcf.woff') format('woff');
            font-weight: 700;
            font-display: swap;
        }
        @font-face {
            font-family: Lato;
            src: url('fonts/lato-italic-dcf.woff2') format('woff2'),
            url('fonts/lato-italic-dcf.woff') format('woff');
            font-style: italic;
            font-display: swap;
        }
        body {
            font-family: sans-serif;
        }
        .fonts-loaded-1 body {
            font-family: LatoSubset;
        }
        .fonts-loaded-2 body {
            font-family: Lato;
        }
    </style>
    <link rel="stylesheet" href="http://iimjsturek.unl.edu/dcf/example/css/all.min.css">
    <link rel="stylesheet" href="http://iimjsturek.unl.edu/dcf/example/css/print.min.css" media="print">
    <meta name="description" content="Sample Mediahub with DCF Theme">
    <!-- InstanceBeginEditable name="head" -->
    <!-- Place optional header elements here -->
    <!-- InstanceEndEditable -->
</head>
<body class="app">

<header class="dcf-header" id="dcf-header" role="banner">
    <!-- InstanceBeginEditable name="titlegraphic" -->
    <a class="unl-site-title-short" href="https://www.unl.edu/">
        Web Application
    </a>
    <!-- InstanceEndEditable -->
    <nav class="dcf-nav-menu dcf-modal-parent dcf-d-none@print" id="dcf-navigation" role="navigation" aria-label="local navigation">
        <div class="dcf-nav-menu-child dcf-app-controls dcf-w-100%">
            <!-- InstanceBeginEditable name="appcontrols" -->
            <!-- InstanceEndEditable -->
        </div>
    </nav>
</header>

<main class="dcf-wrapper" id="dcf-main" role="main" tabindex="-1">
    <!-- InstanceBeginEditable name="maincontentarea" -->
    <p>Impress your audience with awesome content!</p>
    <!-- InstanceEndEditable -->
</main>
<footer class="dcf-footer" id="dcf-footer" role="contentinfo">
    <!-- InstanceBeginEditable name="optionalfooter" -->
    <!-- InstanceEndEditable -->
</footer>
<noscript>
    <div class="" id="dcf-noscript">
        <p>Some parts of this site work best with JavaScript enabled.</p>
    </div>
</noscript>
<script src="http://iimjsturek.unl.edu/wdntemplates/wdn/templates_5.0/js/compressed/all.js?dep=5" id="wdn_dependents"></script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default?flags=gated&amp;features=default%2CElement.prototype.inert" defer=""></script>
<script src="http://iimjsturek.unl.edu/dcf/example/js/bundled/bundle.js" async="" defer=""></script>
<!-- InstanceBeginEditable name="jsbody" -->
<!-- put your custom javascript here -->
<!-- InstanceEndEditable -->
</body>
</html>