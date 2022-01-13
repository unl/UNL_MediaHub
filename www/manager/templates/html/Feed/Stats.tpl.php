<?php
$feed = $context->feed;
$controller->setReplacementData('title', 'UNL | MediaHub | ' . htmlspecialchars($feed->title) . ' Channel Stats');
$baseUrl = UNL_MediaHub_Controller::getURL();
$baseManagerURL = UNL_MediaHub_Manager::getURL();
$user = UNL_MediaHub_AuthService::getInstance()->getUser();
if (!$feed->userCanEdit($user)) {
    throw new UNL_MediaHub_RuntimeException('You do not have permission to manage this channel.', 403);
}
?>
<div class="dcf-mt-6 dcf-mb-6">
    <div class="dcf-float-right mh-rss"><a href="?format=xml" title="RSS feed for this channel"><?php echo \UNL\Templates\Icons::get(\UNL\Templates\Icons::ICON_RSS_BOX, '{"size": 4}'); ?></a></div>
    <h2><?php echo UNL_MediaHub::escape($feed->title) ?> Channel Stats</h2>
    <?php if ($user && $feed->userCanEdit($user)): ?>
        <ul class="dcf-p-1 dcf-list-bare dcf-list-inline dcf-txt-xs dcf-bg-overlay-dark">
            <li class="dcf-m-0">
                <a class="dcf-btn dcf-btn-inverse-tertiary" href="<?php echo $baseUrl; ?>channels/<?php echo (int)$feed->id ?>">View Channel</a>
            </li>
            <li class="dcf-m-0">
                <a class="dcf-btn dcf-btn-inverse-tertiary" href="<?php echo $baseManagerURL; ?>?view=feedmetadata&amp;id=<?php echo (int)$feed->id ?>">Edit Channel</a>
            </li>
            <li class="dcf-m-0">
                <a class="dcf-btn dcf-btn-inverse-tertiary" href="<?php echo $baseManagerURL; ?>?view=permissions&amp;feed_id=<?php echo (int)$feed->id ?>">Edit Channel Users</a>
            </li>
        </ul>
    <?php endif ?>

    <h2 class="dcf-txt-h3">Channel Overview</h2>
    <div class="dcf-grid dcf-col-gap-vw dcf-pt-6">
        <div class="dcf-col-100% dcf-col-25%-start@sm">
            <div class="dcf-ratio dcf-ratio-16x9 mh-channel-thumb">
                <?php $url = htmlentities(UNL_MediaHub_Controller::getURL($feed), ENT_QUOTES) ?>
                <?php if($feed->hasImage()): ?>
                    <img
                            class="dcf-ratio-child dcf-obj-fit-cover"
                            src="<?php echo $url; ?>/image"
                            aria-hidden="true"
                            alt="">
                <?php else: ?>
                    <div class="dcf-ratio-child dcf-d-flex dcf-ai-center dcf-jc-center">
                        <img
                                class="dcf-h-8 dcf-w-8"
                                src="<?php echo $baseUrl; ?>/templates/html/css/images/channel-icon.svg"
                                height="51"
                                width="51"
                                aria-hidden="true"
                                alt="">
                    </div>
                <?php endif; ?>
            </div>
            <?php $counts = $feed->getStats(); ?>
            <ul class="dcf-pl-3 dcf-grid-thirds dcf-list-bare">
                <?php if ($counts['video']): ?>
                    <li class="mh-stat">
                        <span class="mh-count"><?php echo UNL_MediaHub::escape(UNL_MediaHub_Media::formatNumber($counts['video'])) ?></span>
                        <span class="mh-context">Videos</span>
                    </li>
                <?php endif; ?>
                <?php if ($counts['audio']): ?>
                    <li class="mh-stat">
                        <span class="mh-count"><?php echo UNL_MediaHub::escape(UNL_MediaHub_Media::formatNumber($counts['audio'])) ?></span>
                        <span class="mh-context">Audios</span>
                    </li>
                <?php endif; ?>
                <?php if ($counts['plays']): ?>
                    <li class="mh-stat">
                        <span class="mh-count"><?php echo UNL_MediaHub::escape(UNL_MediaHub_Media::formatNumber($counts['plays'], 1)) ?></span>
                        <span class="mh-context">Plays</span>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="dcf-col-100% dcf-col-75%-end@sm">
            <p><?php echo UNL_MediaHub::escape($feed->description) ?></p>
        </div>
    </div>

    <h2 class="dcf-txt-h3">Channel Media</h2>
    <?php if (count($context->media_list->items)): ?>

    <?php
        // Convert feed url into feed stats url
        $feedURL = $context->media_list->getURL(array('page'=>'{%page_number}'));
        $feedURLParts = parse_url($feedURL);
        $url = $baseManagerURL . '?view=feedstats&feed_id=' . $feed->id . '&' . $feedURLParts['query'];
        $pager_layout = new UNL_MediaHub_List_PagerLayout($context->media_list->pager,
            new Doctrine_Pager_Range_Sliding(array('chunk'=>5)),
            htmlentities($url));
        $pager_links = $pager_layout->display(null, true);

        if ($theme->isCustomTheme()) {
            $page->addScriptDeclaration("
                        require(['dcf/dcf-pagination'], function(DCFPaginationModule) {
                            const paginationNavs = document.querySelectorAll('.dcf-pagination');
                            const pagination = new DCFPaginationModule.DCFPagination(paginationNavs);
                            pagination.initialize();
                        });");
        } else {
            $page->addScriptDeclaration("WDN.initializePlugin('pagination');");
        }
    ?>

        <table class="dcf-table dcf-table-striped dcf-table-responsive">
            <thead>
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col">Privacy</th>
                    <th scope="col">Author</th>
                    <th scope="col">Plays</th>
                    <th scope="col">Created</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($context->media_list->items as $media): ?>
                <tr>
                    <td data-label="Title"><a href="<?php echo UNL_MediaHub_Controller::getURL($media) ?>" target="_blank"><?php echo $media->title; ?></a></td>
                    <td data-label="Privacy"><?php echo ucfirst(strtolower($media->privacy)); ?></td>
                    <td data-label="Author"><?php echo $media->author; ?></td>
                    <td class="dcf-txt-right" data-label="Plays"><?php echo $media->play_count; ?></td>
                    <td data-label="Created"><?php echo date('n/j/y', strtotime($media->datecreated)); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php echo $pager_links; ?>
    <?php else: ?>
        <p>No media for channel</p>
    <?php endif; ?>
</div>
