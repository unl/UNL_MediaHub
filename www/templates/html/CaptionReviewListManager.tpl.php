<div class="dcf-bleed dcf-pt-6 dcf-mb-6">
    <div class="dcf-wrapper">
        <h2 class="dcf-txt-h2">Unreviewed Media Captions List</h2>
        <p> Captions for audio content are required for ADA compliance. Please review the list of media items that either have active AI-generated captions that have not been reviewed, or have generated captions pending activation and review. Ensure all AI-generated captions are reviewed for accuracy and context. You can also upload your own captions. </p>

        <div class="dcf-grid dcf-col-gap-vw dcf-row-gap-4">
            <div class="dcf-col-100%">
                <?php if ($context->hasUnreviewedCaptionMedia()) { ?>
                    <h3 class="dcf-txt-h6" id="table-description">List of all media that either have an active AI caption that has not been reviewed or have a generated AI caption that is not yet activated and is pending review. </h3>
                    <table class="dcf-table dcf-table-bordered dcf-table-responsive" aria-describedby="table-description">
                        <thead>
                            <th scope="col">Media</th>
                            <th scope="col">Type</th>
                            <th scope="col">Date Uploaded</th>
                            <th scope="col">Uploaded By</th>
                            <th scope="col">Associated Channels</th>
                            <th scope="col">Actions</th>
                        </thead>
                        <tbody>
                            <tr>
                                <?php foreach ($context->getUnreviewedCaptionMedia() as $media): ?>
                                    <td class="dcf-txt-right" data-label="Title">
                                        <a href="<?php echo $media->getURL(); ?>">
                                            <?php echo htmlspecialchars($media['title']); ?>
                                        </a>
                                    </td>
                                    <td class="dcf-txt-right" data-label="Type"><?php echo htmlspecialchars($media['type']); ?></td>
                                    <td class="dcf-txt-right" data-label="Date"><?php echo htmlspecialchars($media['datecreated']); ?></td>
                                    <td class="dcf-txt-right" data-label="Username"><?php echo htmlspecialchars($media['uidcreated']); ?></td>
                                    <td class="dcf-txt-right" data-label="Channels">
                                        <?php $channels_associated_with_media = [];
                                        $dom = new DOMDocument();

                                        foreach ($media->getFeeds()->items as $channel) {
                                            $a = $dom->createElement('a', htmlspecialchars($channel->title));
                                            $a->setAttribute('href', htmlspecialchars(UNL_MediaHub::escape(UNL_MediaHub_Controller::getURL($channel))));
                                            $channels_associated_with_media[] = $dom->saveHTML($a);
                                        } ?>
                                        <?php echo implode(", ", $channels_associated_with_media); ?>
                                    </td>
                                    <td class="dcf-txt-right" data-label="Actions">
                                        <a href="<?php
                                                    echo htmlspecialchars(UNL_MediaHub_Manager::getURL() . '?view=editcaptions&id=' . $media['id']);
                                                    ?>" class="dcf-btn dcf-btn-primary dcf-mt-1">
                                            <?php
                                            ?>Manage Captions
                                        </a>
                                    </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p class="dcf-bold">There are currently no unreviewed AI-generated captions.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>