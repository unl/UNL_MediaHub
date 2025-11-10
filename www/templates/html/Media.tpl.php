<?php
$type = 'audio';
if ($context->isVideo()) {
    $type = 'video';
    $height = 529;
    $width = 940;
    $dimensions = $context->getVideoDimensions();
    if (isset($dimensions['width'])) {
        // Scale everything down to 450 wide
        $height = round(($width/$dimensions['width'])*$dimensions['height']);
    }
}

// Set start time param (t) if valid in query string for media iframe URL
$time = 0;
if (!empty($_GET['t'])) {
    $time = filter_input(INPUT_GET, 't', FILTER_VALIDATE_FLOAT);
}
$timeParam = !empty($time) ? '&t=' . $time : '';

$user = UNL_MediaHub_AuthService::getInstance()->getUser();

$context->loadReference('UNL_MediaHub_Media_Comment');
$controller->setReplacementData('title', htmlspecialchars($context->title) . ' | MediaHub | University of Nebraska-Lincoln');
// TODO: disable breadcrumbs since currently not supported in 5.0 App templates
//$controller->setReplacementData('breadcrumbs', '<ol> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'">MediaHub</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'search/">All Media</a></li> <li>'.htmlspecialchars($context->title).'</li></ol>');
$meta = '
<meta property="og:type" content="'.$type.'">
<meta property="og:title" content="'.htmlentities($context->title, ENT_QUOTES).'" />
<meta property="og:description" content="'.htmlentities(strip_tags($context->description), ENT_QUOTES).'" />
<meta property="og:image" content="'.$context->getThumbnailURL().'" />
<meta property="og:url" content="'.$context->getURL().'">
';

if ($context->privacy !== 'PUBLIC') {
    $meta .= '<meta name="robots" content="noindex">';
}

if ($type == 'video') {
    $meta .= '
    <meta property="og:video:height" content="'.$height.'" />
    <meta property="og:video:width" content="'.$width.'" />
    <meta property="og:video:url" content="'.$context->getMediaURL().'" />
    <meta property="og:video:secure_url" content="'.$context->getMediaURL().'" />
    <meta property="og:video:type" content="video/mp4" />
    ';
} else {
    $meta .= '
    <meta property="og:audio" content="'.$context->url.'" />
    <meta property="og:audio:title" content="'.htmlentities($context->title, ENT_QUOTES).'" />
    ';
}
$controller->setReplacementData('head', $meta);
$getTracks = $context->getTextTrackURLs();

$divClass = 'dcf-ratio dcf-ratio-16x9 dcf-flex-grow-1';
$iframeClass = 'dcf-ratio-child dcf-obj-fit-contain dcf-obj-top dcf-b-0';
if ($type === 'audio') {
    $divClass = 'dcf-w-max-xl dcf-flex-grow-1 dcf-overflow-hidden dcf-relative';
    $iframeClass = 'dcf-w-100% dcf-h-auto dcf-b-0';
}

?>

<div class="dcf-bleed mh-video-band">
    <div class="dcf-wrapper dcf-pt-4 dcf-pb-4 dcf-d-flex dcf-jc-center">
        <div class="<?php echo $divClass; ?>">
            <iframe class="<?php echo $iframeClass; ?>" height="667" src="<?php echo $controller->getURL($context)?>?format=iframe&autoplay=0&preload=auto<?php echo $timeParam; ?>" allowfullscreen title="play media"></iframe>
        </div>
    </div>
</div>


<div class="dcf-bleed dcf-pt-8 dcf-pb-8">
    <div class="dcf-wrapper mh-media-page">
        <?php if ($user && $context->userCanEdit($user)): ?>
          <div class="dcf-pb-4">
            <a
                href="<?php echo UNL_MediaHub_Controller::getURL() . 'manager/?view=addmedia&id=' . $context->id ?>"
                class="dcf-btn dcf-btn-primary"
            >
                <div class="dcf-d-flex dcf-jc-center dcf-ai-center dcf-col-gap-2">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="dcf-h-4 dcf-w-4 dcf-d-block dcf-fill-current"
                        viewBox="0 0 24 24"
                    >
                        <path d="M23.077,3.96c-0.073-0.143-0.209-0.241-0.367-0.267c-0.158-0.026-0.318,
                            0.027-0.431,0.14l-3.165,3.156h-1.958V4.884 l3.087-3.166c0.111-0.115,
                            0.161-0.274,0.136-0.431c-0.026-0.156-0.125-0.29-0.266-0.364
                            c-0.98-0.501-2.088-0.767-3.203-0.767 c-1.886,0-3.653,0.728-4.974,
                            2.049c-2.075,2.075-2.64,5.169-1.46,7.804l-9.577,9.568c-0.976,
                            0.977-0.98,2.556-0.003,3.533 c0.976,0.976,2.554,0.979,3.531,
                            0.002l9.566-9.568c0.894,0.4,1.875,0.608,2.858,0.608h0c1.868,
                            0,3.623-0.726,4.944-2.048 C23.952,9.949,24.466,6.675,23.077,3.96z"></path>
                        <g>
                            <path fill="none" d="M0 0H24V24H0z"></path>
                        </g>
                    </svg>
                    Edit Media Details
                </div>
            </a>
          </div>
        <?php endif; ?>
        <div class="dcf-grid dcf-col-gap-vw dcf-row-gap-4">
            <div class="dcf-col-100% dcf-col-75%-start@sm">
                <h1><?php echo UNL_MediaHub::escape($context->title); ?></h1>
                <?php if ($element = UNL_MediaHub_Feed_Media_NamespacedElements_itunes::mediaHasElement($context->id, 'subtitle')): ?>
                    <p class="unl-font-sans itunes_subtitle"><?php echo UNL_MediaHub::escape($element->value) ?></p>
                <?php endif; ?>
                <?php
                $purifier = new HTMLPurifier();
                $summary = strip_tags($context->description, "<a><br><p><ul><ol><li><strong><em>");
                $summary = $purifier->purify($summary);

                if ($element = UNL_MediaHub_Feed_Media_NamespacedElements_itunes::mediaHasElement($context->id, 'summary')) {
                    $summary .= '<div class="itunes_summary">'.$element->value.'</div>';
                }
                ?>

                <div class="mh-authoring dcf-grid-thirds@sm dcf-col-gap-vw dcf-txt-center">
                    <?php if (!empty($context->author)): // @TODO present author with more info (standardize people records) ?>
                        <div class="mh-stat">
                            <span class="mh-count"><?php echo UNL_MediaHub::escape($context->author); ?></span>
                            <span class="mh-context unl-font-sans">Author</span>
                        </div>
                    <?php endif; ?>
                    <div class="mh-stat">
                        <span class="mh-count"><?php echo date('m/d/Y', strtotime($context->datecreated)); ?></span>
                        <span class="mh-context unl-font-sans">Added</span>
                    </div>
                    <div class="mh-stat">
                        <span class="mh-count"><?php echo UNL_MediaHub::escape($context->play_count) ?></span>
                        <span class="mh-context unl-font-sans">Plays</span>
                    </div>
                </div>
                <hr>

                <h2 class="unl-font-sans">Description</h2>
                <div class="mh-summary"><?php echo $summary; ?></div>
                <hr>

                <?php if($getTracks): ?>
                    <div class="mediahub-onpage-captions">
                        <?php echo $savvy->render($context, 'MediaPlayer/Transcript.tpl.php'); ?>
                    </div>
                    <hr>
                <?php endif; ?>

                <?php if (($tags = $context->getTags()) || ($user && $context->userCanEdit($user))): ?>
                    <ul id="mediaTags" class="unl-font-sans">
                        <li class="unl-font-sans mh-tag-label">Tags:</li>
                        <?php foreach ($tags as $tag): ?>
                            <li><a href="<?php echo UNL_MediaHub_Controller::$url.'tags/'.urlencode(trim($tag)) ?>"><?php echo UNL_MediaHub::escape($tag) ?></a></li>
                        <?php endforeach; ?>
                        <?php if ($user && $context->userCanEdit($user)): ?>
                            <li id="mediaTagsAdd">
                                <a href="#" title="Add a tag"></a>
                                <form class="dcf-form" id="addTags" method="post">
                                    <label for="new_tag">Please enter a tag name
                                       <input type="text" value="" name="tags" id="new_tag" >
                                    </label>
                                    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
                                    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">
                                    <input class="dcf-btn dcf-btn-primary" type="submit" value="Add" >
                                </form>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <hr>
                <?php endif; ?>

                <div id="comments">
                    <?php
                        if (!empty($page)) {
                            $page->head .= '<link rel="stylesheet" type="text/css" href="../templates/html/css/comments.css?v=' . trim(UNL_MediaHub_Controller::getVersion()) . '" />';
                        }
                    ?>
                    <h2 class="unl-font-sans">Comments <?php echo \UNL\Templates\Icons::get(\UNL\Templates\Icons::ICON_COMMENT, '{"size": 4}');?></h2>
                    <span class="subhead">
                        <?php echo count($context->UNL_MediaHub_Media_Comment); ?> Comments
                        <?php if ($user): ?>
                            | <a href="#commentForm">Leave Yours</a>
                        <?php endif; ?>
                    </span>
                    <?php if (count($context->UNL_MediaHub_Media_Comment)): ?>
                        <ul>
                            <?php foreach ($context->UNL_MediaHub_Media_Comment as $comment): ?>
                                <li>
                                    <?php if ($name = UNL_Services_Peoplefinder::getFullName($comment['uid'])): ?>
                                    <?php endif; ?>

                                    <blockquote><?php echo UNL_MediaHub::escape(strip_tags($comment['comment'])); ?></blockquote>
                                    <div class="mh-user">
                                        <img alt="Your Profile Pic" src="https://directory.unl.edu/avatar/<?php echo UNL_MediaHub::escape($comment['uid']); ?>/?s=small" class="profile_pic small dcf-h-7 dcf-w-7">
                                        <div class="commenter unl-font-sans sec_header clear-top"><?php echo $name; ?></div>
                                        <em><?php echo date('m/d/y g:i a', strtotime($comment['datecreated'])); ?></em>
                                    </div>
                                    <div class="clear"></div>
                                    <hr>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>

                <?php if ($user): ?>
                    <?php $form = new UNL_MediaHub_Media_Comment_Form(); ?>
                    <?php echo $savvy->render($form); ?>
                <?php else: ?>
                    <a href="https://shib.unl.edu/idp/profile/cas/login?service=<?php echo urlencode(UNL_MediaHub_Controller::getURL($context)) ?>">Log in to post comments</a>
                <?php endif; ?>

            </div>

            <div class="dcf-col-100% dcf-col-25%-end@sm mh-sidebar">

              <div class="dcf-pt-4">
                <div class="dcf-mb-4">
                    <button
                        class="
                            dcf-btn
                            dcf-btn-secondary
                            mh-hide-bp2
                            dcf-btn-toggle-modal
                            dcf-d-flex
                            dcf-jc-center
                            dcf-ai-center
                            dcf-col-gap-2
                        "
                        type="button"
                        data-toggles-modal="embed-modal"
                        aria-label="Show Media Embed Code"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="dcf-h-4 dcf-w-4 dcf-d-block dcf-fill-current"
                            viewBox="0 0 24 24"
                        >
                            <path d="M21.4,9.4h-6.7V2.6C14.6,1.2,13.5,0,12,0c-1.4,0-2.6,
                                1.2-2.6,2.6l0,6.7H2.6C1.2,9.4,0,10.6,0,12c0,1.4,1.2,2.6,
                                2.6,2.6h6.8l0,6.7c0,0.7,0.3,1.4,0.8,1.9c0.5,0.5,1.2,0.8,
                                1.9,0.8c1.4,0,2.6-1.2,2.6-2.6v-6.7h6.7c1.4,0,2.6-1.2,
                                2.6-2.6C24,10.6,22.8,9.4,21.4,9.4z"/>
                            <g>
                                <path fill="none" d="M0,0h24v24H0V0z"/>
                            </g>
                        </svg>
                        Embed
                    </button>
                </div>

                  <?php
                  $channels = $context->getFeeds();
                  echo $savvy->render($channels, 'CompactFeedList.tpl.php');
                  ?>

              </div>

            </div>
        </div>
    </div>
</div>

<div class="dcf-modal" id="embed-modal" hidden>
    <div class="dcf-modal-wrapper">
        <header class="dcf-modal-header">
            <h2 id="embed-modal-heading">Embed</h2>
            <button class="dcf-btn-close-modal">Close</button>
        </header>
        <div class="dcf-modal-content">
            <?php $embed = $savvy->render(UNL_MediaHub_Media_Embed::getById($context->id, UNL_MediaHub_Controller::$current_embed_version)); ?>

            <p>Copy the following code into your page</p>
            <div class="dcf-grid-full dcf-rounded dcf-overflow-hidden dcf-mb-5">
                <div class="dcf-ai-center unl-bg-scarlet unl-cream" style="display: grid; grid-template-columns: 1fr auto;">
                    <p class="dcf-m-0 dcf-p-0 dcf-pl-3">
                        <span class="dcf-bold">HTML</span>
                    </p>
                    <button class="dcf-btn dcf-btn-primary dcf-m-1 dcf-d-flex dcf-ai-center copyCodeSnippet" type="button">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="dcf-h-5 dcf-w-5 dcf-fill-current"
                            focusable="false"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            aria-labelledby="filled-copy-1-basic-title"
                        >
                            <title id="filled-copy-1-basic-title">Copy Code Snippet</title>
                            <path
                                d="M5.5,22C5.224,22,5,21.776,5,21.5V3H3.5C3.224,3,3,3.224,3,3.5v20C3,23.776,
                                3.224,24,3.5,24h14c0.276,0,0.5-0.224,0.5-0.5 V22H5.5z"
                            ></path>
                            <path
                                d="M21,6.5c0-0.133-0.053-0.26-0.146-0.353l-6-6C14.76,0.053,14.632,0,14.5,
                                0h-8C6.224,0,6,0.224,6,0.5v20 C6,20.776,6.224,21,6.5,21h14c0.276,0,
                                0.5-0.224,0.5-0.5V6.5z M14,7V1l6,6H14z"
                            ></path>
                            <g>
                                <path fill="none" d="M0 0H24V24H0z"></path>
                            </g>
                        </svg>
                    </button>
                </div>
                <pre
                    class="dcf-m-0 dcf-sharp dcf-p-3 dcf-overflow-x-auto dcf-lh-1"
                    style="background-color: var(--bg-code);"
                ><?php echo htmlentities($embed, ENT_COMPAT | ENT_HTML401, "UTF-8"); ?></pre>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.copyCodeSnippet').forEach((copyButton) => {
        copyButton.addEventListener('click', async () => {
            const codeToCopy = copyButton.parentElement.nextElementSibling.innerText;
            await navigator.clipboard.writeText(codeToCopy);
        });
    });
</script>

