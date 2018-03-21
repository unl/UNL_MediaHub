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
        <div class="title wdn-sans-serif">
            <span class="wdn-icon-search" aria-hidden="true"></span>
            <h2 class="wdn-sans-serif">Searchable Transcript</h2>
            <div class="mh-tooltip italic">
                <span class="wdn-icon-info" aria-hidden="true"></span><span class="wdn-text-hidden">more info</span>
                <div>
                    <ul>
                        <li>Use the text input to search the transcript. </li>
                        <li>Click any line to jump to that spot in the video. </li>
                        <li>Use the icons to the right to toggle between list and paragraph view. </li>
                    </ul>
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
                <span class="wdn-text-hidden">Toggle between list and paragraph view.</span>
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


