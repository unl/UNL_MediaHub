<?php 
    $file = false;
    try {
        $vtt = new Captioning\Format\WebvttFile();
        $tracks = $context->getTextTracks();
        $file = $vtt->loadFromString(trim(reset($tracks)));
    }catch(Exception $err){
    }

    $adapter = new \Conversio\Adapter\LanguageCode();
    $options = new \Conversio\Adapter\Options\LanguageCodeOptions();
    $options->setOutput('native');
    $converter = new \Conversio\Conversion($adapter);
    $converter->setAdapterOptions($options);
?>

<?php  if($file): ?>
<div class="mh-caption-search">
    <div class="mh-transcript-hide-bp2">
        <div class="title unl-font-sans">
            <?php echo \UNL\Templates\Icons::get(\UNL\Templates\Icons::ICON_SEARCH, '{"size": 5}'); ?>
            <h2 class="unl-font-sans">Searchable Transcript</h2>
            <div class="dcf-popup dcf-d-inline" id="title-details" data-hover="true" data-point="true">
                <button class="dcf-btn dcf-btn-tertiary dcf-btn-popup dcf-p-0" type="button">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="dcf-d-block dcf-h-3 dcf-w-3 dcf-fill-current"
                        viewBox="0 0 24 24"
                    >
                        <path d="M11.5,1C5.159,1,0,6.159,0,12.5C0,18.841,5.159,24,11.5,24
                            S23,18.841,23,12.5C23,6.159,17.841,1,11.5,1z M11.5,23 C5.71,23,1,18.29,1,12.5
                            C1,6.71,5.71,2,11.5,2S22,6.71,22,12.5C22,18.29,17.29,23,11.5,23z"></path>
                        <path d="M14.5,19H12v-8.5c0-0.276-0.224-0.5-0.5-0.5h-2
                            C9.224,10,9,10.224,9,10.5S9.224,11,9.5,11H11v8H8.5 C8.224,19,8,19.224,8,19.5
                            S8.224,20,8.5,20h6c0.276,0,0.5-0.224,0.5-0.5S14.776,19,14.5,19z"></path>
                        <circle cx="11" cy="6.5" r="1"></circle>
                        <g>
                            <path fill="none" d="M0 0H24V24H0z"></path>
                        </g>
                    </svg>
                </button>
                <div
                    class="
                        dcf-popup-content
                        unl-cream
                        unl-bg-blue
                        dcf-p-1
                        dcf-rounded
                    "
                    style="min-width: 25ch;"
                >
                    <p class="dcf-m-0 dcf-regular">
                        <ul>
                            <li>Use the text input to search the transcript.</li>
                            <li>Click any line to jump to that spot in the video.</li>
                            <li>Use the icons to the right to toggle between list and paragraph view.</li>
                        </ul>
                    </p>
                </div>
            </div>
            <button class="mh-caption-search-close caption-toggle" aria-label="Close Searchable Transcript">x</button>
            <select id="mh-language-select" aria-label="select language for searchable transcript">
                <?php $languageTracks = array_keys($tracks); ?>
                <?php foreach ($languageTracks as $languageTrack): ?>
                        <option value='<?php echo UNL_MediaHub::escape($languageTrack); ?>'> <?php echo UNL_MediaHub::escape($converter->filter($languageTrack)); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mh-caption-container">
            <label for="mh-parse-caption">Search:</label>
            <a class="mh-paragraph-icons" href="javascript:void(0);">
                <span class="dcf-sr-only">Toggle between list and paragraph view.</span>
                <div class="mh-bullets"></div>
                <div class="mh-paragraph"></div>
            </a>
            <br>
            <input type="text" id="mh-parse-caption"><div class="mh-caption-close"></div>
            <ul class="mh-transcript">
                <?php foreach ($file->getCues() as $line) : ?>
                    <li><a class="highlight" href="javascript:void(0);"><span>[<?php echo ($line->getstart()); ?>]</span><?php echo strip_tags($line->getText()); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="mh-transcript-show-bp2 mh-too-small-message">
        <span>The screen size you are trying to search captions on is too small!</span>
        <p>You can always <a href="<?php echo $context->getURL(); ?>" target="_blank">jump over to MediaHub</a> and check it out there.</p>
    </div>
</div>
<?php endif; ?>


