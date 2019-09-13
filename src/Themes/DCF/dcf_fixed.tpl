<!DOCTYPE html>
<html class="no-js" lang="en"><!-- InstanceBegin template="/Templates/debug.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    <!-- InstanceBeginEditable name="doctitle" -->
    <title>Digital Campus Framework &middot; Documentation</title>
    <!-- InstanceEndEditable -->

    <link rel="preload" href="http://iimjsturek.unl.edu/dcf/example/fonts/lato-subset-dcf.woff2" as="font" type="font/woff2" crossorigin>
    <style>
        @font-face {
            font-family: LatoSubset;
            src: url('http://iimjsturek.unl.edu/dcf/example/fonts/lato-subset-dcf.woff2') format('woff2'),
            url('http://iimjsturek.unl.edu/dcf/example/fonts/lato-subset-dcf.woff') format('woff');
        }
        @font-face {
            font-family: Lato;
            src: url('http://iimjsturek.unl.edu/dcf/example/fonts/lato-regular-dcf.woff2') format('woff2'),
            url('http://iimjsturek.unl.edu/dcf/example/fonts/lato-regular-dcf.woff') format('woff');
            font-display: swap;
        }
        @font-face {
            font-family: Lato;
            src: url('http://iimjsturek.unl.edu/dcf/example/fonts/lato-bold-dcf.woff2') format('woff2'),
            url('http://iimjsturek.unl.edu/dcf/example/fonts/lato-bold-dcf.woff') format('woff');
            font-weight: 700;
            font-display: swap;
        }
        @font-face {
            font-family: Lato;
            src: url('http://iimjsturek.unl.edu/dcf/example/fonts/lato-italic-dcf.woff2') format('woff2'),
            url('http://iimjsturek.unl.edu/dcf/example/fonts/lato-italic-dcf.woff') format('woff');
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
    <script>
      (function() {
        "use strict";
        // Optimization for Repeat Views
        if( sessionStorage.fontsLoadedCriticalFoftPreloadFallback ) {
          document.documentElement.className += " fonts-loaded-2";
          return;
        } else if( "fonts" in document ) {
          document.fonts.load("1em LatoSubset").then(function () {
            document.documentElement.className += " fonts-loaded-1";
            Promise.all([
              document.fonts.load("400 1em Lato"),
              document.fonts.load("700 1em Lato"),
              document.fonts.load("italic 1em Lato")
            ]).then(function () {
              document.documentElement.className += " fonts-loaded-2";
              // Optimization for Repeat Views
              sessionStorage.fontsLoadedCriticalFoftPreloadFallback = true;
            });
          });
        } else {
          // use fallback
          var ref = document.getElementsByTagName( "script" )[ 0 ];
          var script = document.createElement( "script" );
          script.src = "http://iimjsturek.unl.edu/dcf/example/js/app/critical-foft-preload-fallback-optional.js";
          script.async = true;
          ref.parentNode.insertBefore( script, ref );
        }
      })();
    </script>
    <link rel="stylesheet" href="http://iimjsturek.unl.edu/dcf/example/css/all.min.css">
    <link rel="stylesheet" href="http://iimjsturek.unl.edu/dcf/example/css/print.min.css" media="print">
    <meta name="description" content="The Digital Campus Framework is an open-source web framework for higher education institutions.">
    <!--
<link rel="apple-touch-icon" sizes="57x57" href="/includes/icons/apple-touch-icon-57x57.png?v=m223gpjb0w">
<link rel="apple-touch-icon" sizes="60x60" href="/includes/icons/apple-touch-icon-60x60.png?v=m223gpjb0w">
<link rel="apple-touch-icon" sizes="72x72" href="/includes/icons/apple-touch-icon-72x72.png?v=m223gpjb0w">
<link rel="apple-touch-icon" sizes="76x76" href="/includes/icons/apple-touch-icon-76x76.png?v=m223gpjb0w">
<link rel="apple-touch-icon" sizes="114x114" href="/includes/icons/apple-touch-icon-114x114.png?v=m223gpjb0w">
<link rel="apple-touch-icon" sizes="120x120" href="/includes/icons/apple-touch-icon-120x120.png?v=m223gpjb0w">
<link rel="apple-touch-icon" sizes="144x144" href="/includes/icons/apple-touch-icon-144x144.png?v=m223gpjb0w">
<link rel="apple-touch-icon" sizes="152x152" href="/includes/icons/apple-touch-icon-152x152.png?v=m223gpjb0w">
<link rel="apple-touch-icon" sizes="180x180" href="/includes/icons/apple-touch-icon-180x180.png?v=m223gpjb0w">
<link rel="icon" type="image/png" href="/includes/icons/favicon-32x32.png?v=m223gpjb0w" sizes="32x32">
<link rel="icon" type="image/png" href="/includes/icons/favicon-194x194.png?v=m223gpjb0w" sizes="194x194">
<link rel="icon" type="image/png" href="/includes/icons/favicon-96x96.png?v=m223gpjb0w" sizes="96x96">
<link rel="icon" type="image/png" href="/includes/icons/android-chrome-192x192.png?v=m223gpjb0w" sizes="192x192">
<link rel="icon" type="image/png" href="/includes/icons/favicon-16x16.png?v=m223gpjb0w" sizes="16x16">
<link rel="manifest" href="/includes/icons/manifest.json?v=m223gpjb0w">
<link rel="mask-icon" href="/includes/icons/safari-pinned-tab.svg?v=m223gpjb0w">
<link rel="shortcut icon" href="/includes/icons/favicon.ico?v=m223gpjb0w">
<meta name="msapplication-TileColor" content="#fefdfa">
<meta name="msapplication-TileImage" content="/includes/icons/mstile-144x144.png?v=m223gpjb0w">
<meta name="msapplication-config" content="/includes/icons/browserconfig.xml?v=m223gpjb0w">
<meta name="theme-color" content="#fefdfa">
-->
    <!-- InstanceBeginEditable name="head" -->
    <!-- Place optional header elements here -->
    <!-- InstanceEndEditable -->
</head>
<body class="debug example" data-version="1.0">
<nav class="dcf-absolute dcf-pin-top dcf-pin-left dcf-mt-1 dcf-ml-1 dcf-z-1" id="dcf-skip-nav" role="navigation">
    <a class="dcf-show-on-focus dcf-btn dcf-btn-primary" href="#dcf-main">Skip to main content</a>
</nav>

<header class="dcf-header" id="dcf-header" role="banner">
    <div class="dcf-header-global dcf-wrapper dcf-d-flex dcf-flex-row dcf-flex-nowrap dcf-ai-center dcf-jc-between dcf-relative">
        <a class="dcf-institution-title dcf-flex-shrink-0 dcf-pt-3 dcf-pb-3 dcf-txt-xs example-ls-1" href="#"><span class="dcf-uppercase">University of DCF</span></a>
        <nav class="dcf-nav-global dcf-header-global-child dcf-d-flex dcf-jc-flex-end dcf-txt-xs" role="navigation" aria-label="global navigation">
            <ul class="dcf-list-bare dcf-d-flex dcf-mb-0">
                <li class="dcf-d-flex dcf-mb-0"><a class="dcf-pt-4 dcf-pb-4" href="#">Visit</a></li>
                <li class="dcf-d-flex dcf-mb-0"><a class="dcf-pt-4 dcf-pb-4" href="#">Apply</a></li>
                <li class="dcf-d-flex dcf-mb-0"><a class="dcf-pt-4 dcf-pb-4" href="#">Give</a></li>
            </ul>
        </nav>

        <nav class="dcf-idm dcf-header-global-child dcf-flex-grow-1 dcf-jc-flex-end dcf-h-100% dcf-w-min-0 dcf-d-none@print" id="dcf-idm" role="navigation" aria-labelledby="dcf-idm-username">
            <a class="dcf-idm-login dcf-mobile-nav-toggle dcf-idm-toggle" id="dcf-idm-username" href="#">
    <span class="dcf-d-flex dcf-ai-center dcf-jc-center dcf-h-100%">
      <svg class="dcf-icon dcf-fill-current dcf-h-3 dcf-w-3" aria-hidden="true" focusable="false" height="16" width="16" viewBox="0 0 48 48"><path d="M24 48A24 24 0 1 0 0 24a24 24 0 0 0 24 24zm0-38a8 8 0 0 1 4.14 14.84l1.85 12A1 1 0 0 1 29 38H19a1 1 0 0 1-1-1.15l1.85-12A8 8 0 0 1 24 10z"/></svg>
        <span class="dcf-pl-2 dcf-txt-xs">Log In</span>
    </span>
            </a>
            <div id="dcf-idm-notice-container" hidden>
                <button class="dcf-p-0 dcf-b-0 dcf-bg-transparent dcf-mobile-nav-toggle dcf-idm-toggle" id="dcf-idm-toggle" aria-haspopup="true" aria-pressed="false" aria-controls="dcf-idm-options">
      <span class="dcf-d-flex dcf-flex-col dcf-ai-center dcf-jc-center dcf-h-100%">
        <img class="dcf-d-block dcf-circle dcf-h-6 dcf-w-6 example-bg-brand-alpha" src="#" alt="">
        <span class="dcf-sr-only">View your account, </span><span class="dcf-txt-xs">Ryan</span>
      </span>
                </button>
                <nav class="dcf-d-none dcf-overlay-dark dcf-idm-options" id="dcf-idm-options" aria-hidden="true">
                    <ul class="dcf-list-bare">
                        <li><a id="dcf-idm-profile" href="#">View Profile</a></li>
                        <li><a id="dcf-idm-logout" href="#">Log Out</a></li>
                    </ul>
                </nav>
            </div>
        </nav>

        <div class="dcf-search dcf-header-global-child dcf-flex-grow-1 dcf-jc-flex-end dcf-modal-parent dcf-d-none@print" id="dcf-search" role="search">
            <button class="dcf-p-0 dcf-b-0 dcf-bg-transparent dcf-mobile-nav-toggle dcf-search-toggle example-brand-alpha" id="dcf-search-toggle" aria-pressed="false" aria-controls="dcf-search-form" aria-haspopup="true">
    <span class="dcf-d-flex dcf-jc-center dcf-ai-center dcf-h-100%">
      <svg class="dcf-icon dcf-fill-current dcf-h-3 dcf-w-3" aria-hidden="true" focusable="false" width="16" height="16" viewBox="0 0 48 48"><path d="M18 36a17.9 17.9 0 0 0 11.27-4l15.31 15.41a2 2 0 0 0 2.84-2.82L32.08 29.18A18 18 0 1 0 18 36zm0-32A14 14 0 1 1 4 18 14 14 0 0 1 18 4z"/></svg>
      <span class="dcf-pl-2 dcf-txt-xs">Search</span>
  </span>
            </button>
            <form class="dcf-input-group dcf-sr-only" id="dcf-search-form" action="#" method="get">
                <label class="dcf-mr-4" for="dcf-search_query">Search</label>
                <input class="dcf-input-text dcf-search-input" id="dcf-search_query" name="q" type="search" required>
                <button class="dcf-btn dcf-btn-primary dcf-txt-md" type="submit">
        <span class="dcf-d-flex dcf-ai-center dcf-jc-center">
          <svg class="dcf-icon" aria-hidden="true" focusable="false" height="16" width="16" viewBox="0 0 48 48"><path fill="#fff" d="M18 36a17.9 17.9 0 0 0 11.27-4l15.31 15.41a2 2 0 0 0 2.84-2.82L32.08 29.18A18 18 0 1 0 18 36zm0-32A14 14 0 1 1 4 18 14 14 0 0 1 18 4z"/></svg>
        </span>
                    <span class="dcf-sr-only">Submit</span>
                </button>
            </form>
        </div>

    </div>

    <div class="dcf-wrapper dcf-d-flex dcf-ai-center dcf-pt-4 dcf-logo dcf-overflow-hidden">
        <a id="dcf-header-logo" href="#" aria-label="Go to home page"><!-- Logo SVG goes here --></a>
        <div class="dcf-d-flex dcf-flex-col dcf-jc-center">

            <div class="dcf-uppercase dcf-txt-h3 dcf-lh-4 dcf-regular example-ls-2" id="dcf-site-affiliation"><!-- InstanceBeginEditable name="affiliation" --><a href="#">College of Business</a><!-- InstanceEndEditable --></div>

            <div class="dcf-txt-xs dcf-italic" id="dcf-site-title">
                <!-- InstanceBeginEditable name="titlegraphic" -->
                <!-- site title added by app here -->
                <!-- InstanceEndEditable -->
            </div>
        </div>
    </div>

    <nav class="dcf-nav-toggle-group dcf-fixed dcf-pin-bottom dcf-h-9 dcf-w-100% dcf-bg-white dcf-d-none@print" role="navigation" aria-label="Navigate, search, or log in">
        <button class="dcf-nav-toggle-btn dcf-nav-toggle-btn-menu dcf-d-flex dcf-flex-col dcf-ai-center dcf-jc-center dcf-h-9 dcf-p-0 dcf-b-0 dcf-bg-transparent example-brand-alpha" id="dcf-mobile-toggle-menu" aria-expanded="false">
            <svg class="dcf-txt-sm dcf-h-6 dcf-w-6 dcf-fill-current" aria-hidden="true" focusable="false" width="16" height="16" viewBox="0 0 24 24">
                <path d="M23.5 12.5H.5c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h23c.3 0 .5.2.5.5s-.2.5-.5.5zM23.5 4.5H.5C.2 4.5 0 4.3 0 4s.2-.5.5-.5h23c.3 0 .5.2.5.5s-.2.5-.5.5zM23.5 20.5H.5c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h23c.3 0 .5.2.5.5s-.2.5-.5.5z"/>
            </svg>
            <svg class="dcf-txt-sm dcf-h-6 dcf-w-6 dcf-fill-current dcf-d-none" aria-hidden="true" focusable="false" width="16" height="16" viewBox="0 0 24 24">
                <path d="M20.5 4.2L4.2 20.5c-.2.2-.5.2-.7 0-.2-.2-.2-.5 0-.7L19.8 3.5c.2-.2.5-.2.7 0 .2.2.2.5 0 .7z"/>
                <path d="M3.5 4.2l16.3 16.3c.2.2.5.2.7 0s.2-.5 0-.7L4.2 3.5c-.2-.2-.5-.2-.7 0-.2.2-.2.5 0 .7z"/>
            </svg>
            <span class="dcf-sr-only">Open </span><span class="dcf-mt-1 dcf-txt-2xs">Menu</span>
        </button>
        <button class="dcf-nav-toggle-btn dcf-nav-toggle-btn-search dcf-d-flex dcf-flex-col dcf-ai-center dcf-jc-center dcf-h-9 dcf-p-0 dcf-b-0 dcf-bg-transparent example-brand-alpha" id="dcf-mobile-toggle-search" aria-pressed="false" aria-haspopup="true">
            <svg class="dcf-txt-sm dcf-h-6 dcf-w-6 dcf-fill-current" aria-hidden="true" focusable="false" height="16" width="16" viewBox="0 0 24 24">
                <path d="M22.5 21.8L15 14.3c1.2-1.4 2-3.3 2-5.3 0-4.4-3.6-8-8-8S1 4.6 1 9s3.6 8 8 8c2 0 3.9-.8 5.3-2l7.5 7.5c.2.2.5.2.7 0 .2-.2.2-.5 0-.7zM9 16c-3.9 0-7-3.1-7-7s3.1-7 7-7 7 3.1 7 7-3.1 7-7 7z"/>
            </svg>
            <svg class="dcf-txt-sm dcf-h-6 dcf-w-6 dcf-fill-current dcf-d-none" aria-hidden="true" focusable="false" width="16" height="16" viewBox="0 0 24 24">
                <path d="M20.5 4.2L4.2 20.5c-.2.2-.5.2-.7 0-.2-.2-.2-.5 0-.7L19.8 3.5c.2-.2.5-.2.7 0 .2.2.2.5 0 .7z"/>
                <path d="M3.5 4.2l16.3 16.3c.2.2.5.2.7 0s.2-.5 0-.7L4.2 3.5c-.2-.2-.5-.2-.7 0-.2.2-.2.5 0 .7z"/>
            </svg>
            <span class="dcf-mt-1 dcf-txt-2xs">Search</span>
        </button>
        <div class="dcf-nav-toggle-btn dcf-nav-toggle-btn-idm dcf-idm dcf-d-flex dcf-flex-col dcf-ai-center dcf-jc-center dcf-h-9">
            <div class="dcf-idm-status-logged-out dcf-h-100% dcf-w-100%">
                <a class="dcf-d-flex dcf-flex-col dcf-ai-center dcf-jc-center dcf-h-100% dcf-w-100% example-brand-alpha" href="">
                    <svg class="dcf-txt-sm dcf-h-6 dcf-w-6 dcf-fill-current" aria-hidden="true" focusable="false" height="16" width="16" viewBox="0 0 24 24">
                        <path d="M12 0C5.4 0 0 5.4 0 12c0 6.2 5 12 12 12 3.2 0 6.3-1.3 8.5-3.6S24 15.1 24 12c0-6.6-5.4-12-12-12zM4.7 20.2c1-.6 2.5-1.1 4-1.6l1.5-.6c.2-.1.3-.3.3-.5V15c0-.2-.1-.4-.3-.5 0 0-1.2-.5-1.2-2.5 0-.3-.2-.5-.5-.5 0 0-.1-.2-.1-.5s.1-.5.1-.5c.3 0 .5-.2.5-.5 0-.1 0-.3-.1-.5-.2-.5-.6-2-.2-2.4.1-.2.5-.2.7-.1.3 0 .5-.1.6-.4.2-.6 1.3-1.1 2.8-1.1s2.6.5 2.8 1.1c.2.9-.2 2.2-.4 2.8-.2.3-.2.5-.2.6 0 .3.2.5.5.5 0 0 .1.2.1.5s-.1.5-.1.5c-.3 0-.5.2-.5.5 0 2.1-1.1 2.5-1.2 2.5-.2.1-.3.3-.3.5v2.5c0 .2.1.4.3.5.5.2 1.1.4 1.6.6 1.5.5 2.9 1.1 3.9 1.6-2 1.8-4.5 2.8-7.3 2.8-2.7 0-5.3-1-7.3-2.8zm15.4-.8c-1-.6-2.6-1.2-4.3-1.8-.4-.2-.8-.3-1.3-.5v-1.8c.5-.3 1.4-1.1 1.5-2.9.4-.2.6-.7.6-1.4 0-.6-.2-1-.5-1.3.2-.8.7-2.1.4-3.3-.3-1.4-2.2-1.9-3.7-1.9-1.3 0-3 .4-3.6 1.5-.5-.1-.9.1-1.2.4-.8.8-.3 2.4-.1 3.3-.3.2-.5.7-.5 1.3 0 .6.2 1.1.6 1.4.1 1.8 1 2.6 1.5 2.9v1.8c-.4.1-.8.3-1.2.4-1.6.6-3.3 1.2-4.4 1.9C2 17.4 1 14.8 1 12 1 5.9 5.9 1 12 1s11 4.9 11 11c0 2.8-1 5.4-2.9 7.4z"/>
                    </svg>
                    <svg class="dcf-txt-sm dcf-h-6 dcf-w-6 dcf-fill-current dcf-d-none" aria-hidden="true" focusable="false" width="16" height="16" viewBox="0 0 24 24">
                        <path d="M20.5 4.2L4.2 20.5c-.2.2-.5.2-.7 0-.2-.2-.2-.5 0-.7L19.8 3.5c.2-.2.5-.2.7 0 .2.2.2.5 0 .7z"/>
                        <path d="M3.5 4.2l16.3 16.3c.2.2.5.2.7 0s.2-.5 0-.7L4.2 3.5c-.2-.2-.5-.2-.7 0-.2.2-.2.5 0 .7z"/>
                    </svg>
                    <span class="dcf-mt-1 dcf-txt-2xs">Log In</span>
                </a>
            </div>
            <div class="dcf-idm-status-logged-in dcf-relative dcf-h-100% dcf-w-100%" hidden></div>
        </div>
    </nav>

    <nav class="dcf-wrapper dcf-pt-5 dcf-nav-local dcf-d-none@print" id="dcf-local-navigation" role="navigation" aria-label="local navigation">
        <!-- InstanceBeginEditable name="navlinks" -->
        <!-- app add nav links here -->
        <!-- InstanceEndEditable -->
    </nav>

</header>
<main class="dcf-wrapper" id="dcf-main" role="main" tabindex="-1">
    <!-- InstanceBeginEditable name="maincontentarea" -->
    <!-- InstanceEndEditable -->
</main>
<footer class="dcf-footer" id="dcf-footer" role="contentinfo">
    <!-- InstanceBeginEditable name="optionalfooter" -->
    <!-- InstanceEndEditable -->
    <!-- InstanceBeginEditable name="contactinfo" -->
    <div class="dcf-wrapper dcf-d-grid dcf-col-gap-4 dcf-footer-local dcf-pt-7 dcf-pb-7" id="dcf-footer-local">
        <div itemscope itemtype="http://schema.org/Organization">
            <h2 class="dcf-mb-4 dcf-txt-md dcf-bold dcf-uppercase example-ls-1" id="dcf-footer-group-1-heading"><span itemprop="name">College of Fine and Performing Arts</span><span class="dcf-sr-only"> Contact Information</span></h2>
            <dl aria-labelledby="dcf-footer-group-1-heading">
                <dt>Address</dt>
                <dd class="dcf-d-inline-block dcf-relative dcf-pl-6">
                    <svg class="dcf-icon dcf-icon-inline dcf-icon-hang" aria-hidden="true" fill="currentcolor" height="16" width="16" viewBox="0 0 48 48"><path d="M24 0A16 16 0 0 0 8 16c0 8.49 14.55 30.61 15.17 31.55a1 1 0 0 0 1.67 0C25.45 46.61 40 24.49 40 16A16 16 0 0 0 24 0zm0 23a7 7 0 1 1 7-7 7 7 0 0 1-7 7z"/></svg>
                    <address itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress" translate="no">
                        <span itemprop="name">College of Fine and Performing Arts</span><br>
                        <span itemprop="streetAddress">1234 Art Building</span><br>
                        Room 123<br>
                        <span itemprop="addressLocality">Anytown</span>, <abbr title="Nebraska" itemprop="addressRegion">NE</abbr> <span itemprop="postalCode">65432</span> <abbr class="dcf-none" itemprop="addressCountry">US</abbr>
                    </address>
                </dd>
                <dt>Phone Number</dt>
                <dd>
                    <a class="dcf-d-inline-block dcf-relative dcf-pl-6" href="tel:+15555554321"><svg class="dcf-icon dcf-icon-inline dcf-icon-hang" aria-hidden="true" focusable="false" height="16" width="16" viewBox="0 0 48 48"><path d="M10 10h28v28H10zM10 40v3a5 5 0 0 0 5 5h18a5 5 0 0 0 5-5v-3zm14 5a2 2 0 1 1 2-2 2 2 0 0 1-2 2zM38 8V5a5 5 0 0 0-5-5H15a5 5 0 0 0-5 5v3zM19 4h10a1 1 0 1 1 0 2H19a1 1 0 0 1 0-2z"/></svg><span itemprop="telephone">555-555-4321</span></a>
                </dd>
                <dt>Fax Number</dt>
                <dd class="dcf-d-inline-block dcf-relative dcf-pl-6">
                    <svg class="dcf-icon dcf-icon-inline dcf-icon-hang" aria-hidden="true" focusable="false" fill="currentcolor" height="16" width="16" viewBox="0 0 48 48"><path d="M1 15v26a3 3 0 0 0 3 3h1V12H4a3 3 0 0 0-3 3zM16 6h-3V1a1 1 0 0 0-2 0v5.1A5 5 0 0 0 7 11v32a5 5 0 0 0 5 5h4a5 5 0 0 0 5-5V11a5 5 0 0 0-5-5zM45 12.18V5a1 1 0 0 0-.37-.78l-5-4A1 1 0 0 0 39 0H26a1 1 0 0 0-1 1v11h-2v32h21a3 3 0 0 0 3-3V15a3 3 0 0 0-2-2.82zM27 2h11.65L43 5.48V12H27zm2 34h-2v-2h2zm0-4h-2v-2h2zm0-4h-2v-2h2zm6 8h-2v-2h2zm0-4h-2v-2h2zm0-4h-2v-2h2zm6 8h-2v-2h2zm0-4h-2v-2h2zm0-4h-2v-2h2zm0-7a1 1 0 0 1-1 1H28a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1z"/></svg><span itemprop="faxNumber">555-555-1234</span>
                </dd>
                <dt>Email Address</dt>
                <dd>
                    <address><a class="dcf-d-inline-block dcf-relative dcf-pl-6" href="mailto:info@example.edu"><svg class="dcf-icon dcf-icon-inline dcf-icon-hang" aria-hidden="true" focusable="false" height="16" width="16" viewBox="0 0 48 48"><path d="M24 0a24 24 0 1 0 13.57 43.82 2 2 0 0 0-2.26-3.3A20 20 0 1 1 44 24v1.91a4.7 4.7 0 0 1-9.39 0V24a10.63 10.63 0 1 0-2.48 6.81A8.69 8.69 0 0 0 48 25.91V24A24 24 0 0 0 24 0zm0 30.61A6.61 6.61 0 1 1 30.61 24 6.62 6.62 0 0 1 24 30.61z"/></svg><span itemprop="email">info@example.edu</span></a></address>
                </dd>
                <dt>Social Media</dt>
                <dd>
                    <address><a class="dcf-d-inline-block dcf-relative dcf-pl-6" href="http://www.facebook.com/"><svg class="dcf-icon dcf-icon-inline dcf-icon-hang" aria-hidden="true" height="16" width="16" viewBox="0 0 48 48"><path d="M48 24.1c0-13.3-10.7-24-24-24S0 10.9 0 24.1c0 12 8.8 21.9 20.2 23.7V31.1h-6.1v-6.9h6.1v-5.3c0-6 3.6-9.3 9.1-9.3 2.6 0 5.4.5 5.4.5V16h-3c-3 0-3.9 1.9-3.9 3.7v4.5h6.7l-1.1 6.9h-5.6v16.8C39.2 46.1 48 36.1 48 24.1z"></path></svg>CFPA<span class="dcf-sr-only"> on Facebook</span></a></address>
                </dd>
                <dd>
                    <address><a class="dcf-d-inline-block dcf-relative dcf-pl-6" href="https://twitter.com/"><svg class="dcf-icon dcf-icon-inline dcf-icon-hang" aria-hidden="true" focusable="false" height="16" width="16" viewBox="0 0 48 48"><path d="M15.1 43.5c18.11 0 28-15 28-28v-1.27A20 20 0 0 0 48 9.11a19.66 19.66 0 0 1-5.66 1.55 9.88 9.88 0 0 0 4.33-5.45 19.74 19.74 0 0 1-6.25 2.39 9.86 9.86 0 0 0-16.78 9A28 28 0 0 1 3.34 6.3a9.86 9.86 0 0 0 3 13.15 9.77 9.77 0 0 1-4.47-1.23v.12A9.85 9.85 0 0 0 9.82 28a9.83 9.83 0 0 1-4.45.17 9.86 9.86 0 0 0 9.2 6.83 19.76 19.76 0 0 1-12.23 4.22A20 20 0 0 1 0 39.08a27.88 27.88 0 0 0 15.1 4.42"></path></svg>cfpa<span class="dcf-sr-only"> on Twitter</span></a></address>
                </dd>
                <dd>
                    <address><a class="dcf-d-inline-block dcf-relative dcf-pl-6" href="https://www.linkedin.com/"><svg class="dcf-icon dcf-icon-inline dcf-icon-hang" aria-hidden="true" focusable="false" height="16" width="16" viewBox="0 0 48 48"><path d="M44.45 0H3.54A3.5 3.5 0 0 0 0 3.46v41.08A3.5 3.5 0 0 0 3.54 48h40.91A3.51 3.51 0 0 0 48 44.54V3.46A3.51 3.51 0 0 0 44.45 0zM14.24 40.9H7.11V18h7.13zm-3.56-26a4.13 4.13 0 1 1 4.13-4.13 4.13 4.13 0 0 1-4.13 4.1zm30.23 26h-7.12V29.76c0-2.66 0-6.07-3.7-6.07s-4.27 2.9-4.27 5.88V40.9h-7.11V18h6.82v3.13h.1a7.48 7.48 0 0 1 6.74-3.7c7.21 0 8.54 4.74 8.54 10.91z"></path></svg>College of Fine and Performing Arts<span class="dcf-sr-only"> on LinkedIn</span></a></address>
                </dd>
                <dd>
                    <address><a class="dcf-d-inline-block dcf-relative dcf-pl-6" href="https://www.youtube.com/"><svg class="dcf-icon dcf-icon-inline dcf-icon-hang" aria-hidden="true" focusable="false" height="16" width="16" viewBox="0 0 48 48"><path d="M47 12.37a6 6 0 0 0-4.25-4.27C39 7.09 24 7.09 24 7.09s-15 0-18.75 1A6 6 0 0 0 1 12.37C0 16.14 0 24 0 24s0 7.86 1 11.63a6 6 0 0 0 4.25 4.27c3.74 1 18.75 1 18.75 1s15 0 18.75-1A6 6 0 0 0 47 35.63C48 31.86 48 24 48 24s0-7.86-1-11.63zM19.09 31.14V16.86L31.64 24z"></path></svg>College of Fine and Performing Arts<span class="dcf-sr-only"> on YouTube</span></a></address>
                </dd>
                <dd>
                    <address><a class="dcf-d-inline-block dcf-relative dcf-pl-6" href="http://instagram.com/"><svg class="dcf-icon dcf-icon-inline dcf-icon-hang" aria-hidden="true" focusable="false" height="16" width="16" viewBox="0 0 48 48"><path d="M24 4.33c6.41 0 7.17 0 9.7.14a13.28 13.28 0 0 1 4.46.83 7.44 7.44 0 0 1 2.76 1.79 7.44 7.44 0 0 1 1.79 2.76 13.28 13.28 0 0 1 .83 4.46c.12 2.53.14 3.29.14 9.7s0 7.17-.14 9.7a13.28 13.28 0 0 1-.83 4.46 8 8 0 0 1-4.55 4.55 13.28 13.28 0 0 1-4.46.83c-2.53.12-3.29.14-9.7.14s-7.17 0-9.7-.14a13.28 13.28 0 0 1-4.46-.83 7.44 7.44 0 0 1-2.76-1.79 7.44 7.44 0 0 1-1.79-2.76 13.28 13.28 0 0 1-.83-4.46c-.12-2.53-.14-3.29-.14-9.7s0-7.17.14-9.7a13.28 13.28 0 0 1 .83-4.46 7.44 7.44 0 0 1 1.8-2.77 7.44 7.44 0 0 1 2.76-1.79 13.28 13.28 0 0 1 4.46-.83c2.53-.12 3.29-.14 9.7-.14M24 0c-6.52 0-7.34 0-9.9.14a17.61 17.61 0 0 0-5.82 1.12A11.76 11.76 0 0 0 4 4a11.76 11.76 0 0 0-2.74 4.28 17.6 17.6 0 0 0-1.12 5.83C0 16.66 0 17.48 0 24s0 7.34.14 9.9a17.6 17.6 0 0 0 1.11 5.82A11.76 11.76 0 0 0 4 44a11.76 11.76 0 0 0 4.25 2.77 17.59 17.59 0 0 0 5.83 1.12c2.55.12 3.38.14 9.9.14s7.34 0 9.9-.14a17.56 17.56 0 0 0 5.82-1.12 12.27 12.27 0 0 0 7-7 17.59 17.59 0 0 0 1.12-5.83c.18-2.6.18-3.42.18-9.94s0-7.34-.14-9.9a17.56 17.56 0 0 0-1.12-5.82A11.76 11.76 0 0 0 44 4a11.76 11.76 0 0 0-4.25-2.77A17.6 17.6 0 0 0 33.9.15C31.34 0 30.52 0 24 0zm0 11.68A12.32 12.32 0 1 0 36.32 24 12.32 12.32 0 0 0 24 11.67zM24 32a8 8 0 1 1 8-8 8 8 0 0 1-8 8zM36.81 8.31a2.88 2.88 0 1 0 2.88 2.88 2.88 2.88 0 0 0-2.88-2.88z"></path></svg>CFPA<span class="dcf-sr-only"> on Instagram</span></a></address>
                </dd>
            </dl>
        </div>
        <div>
            <h2 class="dcf-mb-4 dcf-txt-md dcf-bold dcf-uppercase example-ls-1" id="dcf-footer-group-2-heading">Related Links</h2>
            <ul class="dcf-list-bare" aria-labelledby="dcf-footer-group-2-heading">
                <li><a href="#">Alumni &amp; Friends</a></li>
                <li><a href="#">Emeriti Association</a></li>
            </ul>
        </div>
    </div>

    <!-- InstanceEndEditable -->
    <div class="dcf-wrapper dcf-d-grid dcf-col-gap-4 dcf-footer-global dcf-pt-7" id="dcf-footer-global">
        <div>
            <h2 class="dcf-mb-4 dcf-txt-md dcf-bold dcf-uppercase example-ls-1" id="dcf-footer-group-3-heading">Connect with #DCF</h2>
            <ul class="dcf-list-bare" aria-labelledby="dcf-footer-group-3-heading">
                <li><a class="dcf-d-inline-block dcf-relative dcf-pl-6" href="https://www.facebook.com/"><svg class="dcf-icon dcf-icon-inline dcf-icon-hang" aria-hidden="true" focusable="false" height="16" width="16" viewBox="0 0 48 48"><path d="M48 24.1c0-13.3-10.7-24-24-24S0 10.9 0 24.1c0 12 8.8 21.9 20.2 23.7V31.1h-6.1v-6.9h6.1v-5.3c0-6 3.6-9.3 9.1-9.3 2.6 0 5.4.5 5.4.5V16h-3c-3 0-3.9 1.9-3.9 3.7v4.5h6.7l-1.1 6.9h-5.6v16.8C39.2 46.1 48 36.1 48 24.1z"></path></svg>DCF<span class="dcf-sr-only"> on Facebook</span></a></li>
                <li><a class="dcf-d-inline-block dcf-relative dcf-pl-6" href="https://twitter.com/"><svg class="dcf-icon dcf-icon-inline dcf-icon-hang" aria-hidden="true" focusable="false" height="16" width="16" viewBox="0 0 48 48"><path d="M15.1 43.5c18.11 0 28-15 28-28v-1.27A20 20 0 0 0 48 9.11a19.66 19.66 0 0 1-5.66 1.55 9.88 9.88 0 0 0 4.33-5.45 19.74 19.74 0 0 1-6.25 2.39 9.86 9.86 0 0 0-16.78 9A28 28 0 0 1 3.34 6.3a9.86 9.86 0 0 0 3 13.15 9.77 9.77 0 0 1-4.47-1.23v.12A9.85 9.85 0 0 0 9.82 28a9.83 9.83 0 0 1-4.45.17 9.86 9.86 0 0 0 9.2 6.83 19.76 19.76 0 0 1-12.23 4.22A20 20 0 0 1 0 39.08a27.88 27.88 0 0 0 15.1 4.42"></path></svg>dcf<span class="dcf-sr-only"> on Twitter</span></a></li>
                <li><a class="dcf-d-inline-block dcf-relative dcf-pl-6" href="https://www.youtube.com/"><svg class="dcf-icon dcf-icon-inline dcf-icon-hang" aria-hidden="true" focusable="false" height="16" width="16" viewBox="0 0 48 48"><path d="M47 12.37a6 6 0 0 0-4.25-4.27C39 7.09 24 7.09 24 7.09s-15 0-18.75 1A6 6 0 0 0 1 12.37C0 16.14 0 24 0 24s0 7.86 1 11.63a6 6 0 0 0 4.25 4.27c3.74 1 18.75 1 18.75 1s15 0 18.75-1A6 6 0 0 0 47 35.63C48 31.86 48 24 48 24s0-7.86-1-11.63zM19.09 31.14V16.86L31.64 24z"></path></svg>DCF<span class="dcf-sr-only"> on YouTube</span></a></li>
                <li><a class="dcf-relative dcf-pl-6" href="https://www.instagram.com/"><svg class="dcf-icon dcf-icon-inline dcf-icon-hang" aria-hidden="true" focusable="false" height="16" width="16" viewBox="0 0 48 48"><path d="M24 4.33c6.41 0 7.17 0 9.7.14a13.28 13.28 0 0 1 4.46.83 7.44 7.44 0 0 1 2.76 1.79 7.44 7.44 0 0 1 1.79 2.76 13.28 13.28 0 0 1 .83 4.46c.12 2.53.14 3.29.14 9.7s0 7.17-.14 9.7a13.28 13.28 0 0 1-.83 4.46 8 8 0 0 1-4.55 4.55 13.28 13.28 0 0 1-4.46.83c-2.53.12-3.29.14-9.7.14s-7.17 0-9.7-.14a13.28 13.28 0 0 1-4.46-.83 7.44 7.44 0 0 1-2.76-1.79 7.44 7.44 0 0 1-1.79-2.76 13.28 13.28 0 0 1-.83-4.46c-.12-2.53-.14-3.29-.14-9.7s0-7.17.14-9.7a13.28 13.28 0 0 1 .83-4.46 7.44 7.44 0 0 1 1.8-2.77 7.44 7.44 0 0 1 2.76-1.79 13.28 13.28 0 0 1 4.46-.83c2.53-.12 3.29-.14 9.7-.14M24 0c-6.52 0-7.34 0-9.9.14a17.61 17.61 0 0 0-5.82 1.12A11.76 11.76 0 0 0 4 4a11.76 11.76 0 0 0-2.74 4.28 17.6 17.6 0 0 0-1.12 5.83C0 16.66 0 17.48 0 24s0 7.34.14 9.9a17.6 17.6 0 0 0 1.11 5.82A11.76 11.76 0 0 0 4 44a11.76 11.76 0 0 0 4.25 2.77 17.59 17.59 0 0 0 5.83 1.12c2.55.12 3.38.14 9.9.14s7.34 0 9.9-.14a17.56 17.56 0 0 0 5.82-1.12 12.27 12.27 0 0 0 7-7 17.59 17.59 0 0 0 1.12-5.83c.18-2.6.18-3.42.18-9.94s0-7.34-.14-9.9a17.56 17.56 0 0 0-1.12-5.82A11.76 11.76 0 0 0 44 4a11.76 11.76 0 0 0-4.25-2.77A17.6 17.6 0 0 0 33.9.15C31.34 0 30.52 0 24 0zm0 11.68A12.32 12.32 0 1 0 36.32 24 12.32 12.32 0 0 0 24 11.67zM24 32a8 8 0 1 1 8-8 8 8 0 0 1-8 8zM36.81 8.31a2.88 2.88 0 1 0 2.88 2.88 2.88 2.88 0 0 0-2.88-2.88z"></path></svg>DCF<span class="dcf-sr-only"> on Instagram</span></a></li>
                <li><a class="dcf-d-inline-block dcf-relative dcf-pl-6" href="https://www.linkedin.com/"><svg class="dcf-icon dcf-icon-inline dcf-icon-hang" aria-hidden="true" focusable="false" height="16" width="16" viewBox="0 0 48 48"><path d="M44.45 0H3.54A3.5 3.5 0 0 0 0 3.46v41.08A3.5 3.5 0 0 0 3.54 48h40.91A3.51 3.51 0 0 0 48 44.54V3.46A3.51 3.51 0 0 0 44.45 0zM14.24 40.9H7.11V18h7.13zm-3.56-26a4.13 4.13 0 1 1 4.13-4.13 4.13 4.13 0 0 1-4.13 4.1zm30.23 26h-7.12V29.76c0-2.66 0-6.07-3.7-6.07s-4.27 2.9-4.27 5.88V40.9h-7.11V18h6.82v3.13h.1a7.48 7.48 0 0 1 6.74-3.7c7.21 0 8.54 4.74 8.54 10.91z"></path></svg>Digital Campus Framework<span class="dcf-sr-only"> on LinkedIn</span></a></li>
                <li><a class="dcf-d-inline-block dcf-relative dcf-pl-6" href="https://www.pinterest.com/"><svg class="dcf-icon dcf-icon-inline dcf-icon-hang" aria-hidden="true" focusable="false" height="16" width="16" viewBox="0 0 48 48"><path d="M24 0a24 24 0 0 0-8.75 46.35 23 23 0 0 1 .08-6.88l2.81-11.93a8.66 8.66 0 0 1-.71-3.54c0-3.34 1.93-5.83 4.34-5.83 2 0 3 1.54 3 3.38 0 2.06-1.31 5.14-2 8a3.48 3.48 0 0 0 3.55 4.34c4.27 0 7.54-4.5 7.54-11 0-5.75-4.13-9.76-10-9.76a10.39 10.39 0 0 0-10.8 10.38A9.34 9.34 0 0 0 14.85 29a.72.72 0 0 1 .17.69c-.18.76-.59 2.39-.67 2.72s-.35.53-.8.32c-3-1.4-4.87-5.78-4.87-9.3 0-7.57 5.5-14.52 15.86-14.52 8.33 0 14.8 5.93 14.8 13.86 0 8.27-5.22 14.93-12.45 14.93-2.43 0-4.72-1.26-5.5-2.76l-1.5 5.71a26.83 26.83 0 0 1-3 6.29A24 24 0 1 0 24 0z"></path></svg>dcf<span class="dcf-sr-only"> on Pinterest</span></a></li>
                <li><a class="dcf-d-inline-block dcf-relative dcf-pl-6" href="https://www.spotify.com/"><svg class="dcf-icon dcf-icon-inline dcf-icon-hang" aria-hidden="true" focusable="false" height="16" width="16" viewBox="0 0 48 48"><path d="M24 0a24 24 0 1 0 24 24A24 24 0 0 0 24 0zm11 34.61a1.5 1.5 0 0 1-2.06.5c-5.63-3.44-12.73-4.22-21.08-2.31a1.5 1.5 0 0 1-.67-2.92c9.14-2.09 17-1.19 23.31 2.68a1.5 1.5 0 0 1 .5 2.05zm2.94-6.54a1.87 1.87 0 0 1-2.57.62c-6.45-4-16.28-5.11-23.92-2.8a1.87 1.87 0 1 1-1.09-3.58c8.72-2.64 19.55-1.36 27 3.19a1.87 1.87 0 0 1 .62 2.57zm.25-6.8c-7.74-4.59-20.5-5-27.88-2.78A2.24 2.24 0 1 1 9 14.2c8.48-2.57 22.57-2.08 31.48 3.21a2.24 2.24 0 1 1-2.29 3.86z"></path></svg>dcf<span class="dcf-sr-only"> on Spotify</span></a></li>
                <li><a class="dcf-d-inline-block dcf-relative dcf-pl-6" href="https://www.snapchat.com/"><svg class="dcf-icon dcf-icon-inline dcf-icon-hang" aria-hidden="true" focusable="false" height="16" width="16" viewBox="0 0 48 48"><path d="M24.46 1.44h-.95A12.77 12.77 0 0 0 11.66 9.1c-1.06 2.39-.81 6.44-.6 9.7 0 .38 0 .78.07 1.17a1.91 1.91 0 0 1-.93.2 5.36 5.36 0 0 1-2.2-.58 1.78 1.78 0 0 0-.76-.16A2.38 2.38 0 0 0 4.89 21c-.13.69.18 1.7 2.4 2.58.2.08.44.16.7.24.92.29 2.31.73 2.68 1.62a2.13 2.13 0 0 1-.23 1.76c-.12.28-3.06 7-9.58 8.06a1 1 0 0 0-.83 1 1.34 1.34 0 0 0 .11.45c.49 1.14 2.56 2 6.32 2.57a4.67 4.67 0 0 1 .34 1.14c.08.36.16.73.28 1.13a1.11 1.11 0 0 0 1.16.85 6.05 6.05 0 0 0 1.08-.15 12.67 12.67 0 0 1 2.56-.29 11.27 11.27 0 0 1 1.83.15 8.7 8.7 0 0 1 3.4 1.89c1.71 1.21 3.65 2.58 6.59 2.58h.59c2.94 0 4.88-1.37 6.59-2.58a8.7 8.7 0 0 1 3.44-1.78 11.25 11.25 0 0 1 1.83-.15 12.75 12.75 0 0 1 2.56.27 6 6 0 0 0 1.08.13h.05a1.08 1.08 0 0 0 1.16-.85c.11-.38.19-.75.27-1.11a4.64 4.64 0 0 1 .34-1.13c3.76-.58 5.83-1.42 6.31-2.56a1.32 1.32 0 0 0 .11-.45 1 1 0 0 0-.83-1c-6.53-1.08-9.46-7.77-9.58-8.06a2.13 2.13 0 0 1-.23-1.76c.38-.89 1.76-1.33 2.68-1.62.26-.08.5-.16.7-.24 1.63-.64 2.44-1.43 2.43-2.35a2 2 0 0 0-1.46-1.68 2.67 2.67 0 0 0-1-.19 2.21 2.21 0 0 0-.92.19 5.6 5.6 0 0 1-2.07.59 1.84 1.84 0 0 1-.81-.2c0-.33 0-.67.06-1v-.14c.21-3.26.46-7.32-.6-9.71a12.79 12.79 0 0 0-11.94-7.76z"></path></svg>DCF<span class="dcf-sr-only"> on Snapchat</span></a></li>
            </ul>
        </div>
        <div>
            <h2 class="dcf-mb-4 dcf-txt-md dcf-bold dcf-uppercase example-ls-1" id="dcf-footer-group-4-heading">Campus <span class="dcf-sr-only">Links</span></h2>
            <ul class="dcf-list-bare" aria-labelledby="dcf-footer-group-4-heading">
                <li><a href="#">Directory</a></li>
                <li><a href="#">Employment</a></li>
                <li><a href="#">Events</a></li>
                <li><a href="#">Libraries</a></li>
                <li><a href="#">Maps</a></li>
            </ul>
        </div>
        <div>
            <h2 class="dcf-mb-4 dcf-txt-md dcf-bold dcf-uppercase example-ls-1" id="dcf-footer-group-5-heading">Policies</h2>
            <ul class="dcf-list-bare" aria-labelledby="dcf-footer-group-5-heading">
                <li><a href="#">Emergency Planning and Preparedness</a></li>
                <li><a href="#">Institutional Equity and Compliance</a></li>
                <li><a href="#">Notice of Nondiscrimination</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Student Information Disclosures</a></li>
            </ul>
        </div>
        <small class="dcf-mt-6 dcf-txt-xs">&copy; 2019 University of DCF</small>
    </div>

</footer>
<noscript>
    <div class="" id="dcf-noscript">
        <p>Some parts of this site work best with JavaScript enabled.</p>
    </div>
</noscript>

<script src="https://polyfill.io/v3/polyfill.min.js?flags=gated&features=default" defer></script>
<script src="http://iimjsturek.unl.edu/dcf/example/js/bundled/bundle.js" async defer></script>
<!--<script src="js/bundled/notice.js"></script>-->
<!--<script src="js/bundled/uuid-gen.js"></script>-->

<!-- InstanceBeginEditable name="jsbody" -->
<!-- put your custom javascript here -->
<!-- InstanceEndEditable -->

</body>
<!-- InstanceEnd --></html>
