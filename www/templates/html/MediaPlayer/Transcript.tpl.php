<?php 
    
    $file = false;
    try {
        $vtt = new Captioning\Format\WebvttFile();
        $tracks = $context->getTextTracks();
        $file = $vtt->loadFromString(reset($tracks));
    }catch(Exception $err){
    }

    $adapter = new \Conversio\Adapter\LanguageCode();
    $options = new \Conversio\Adapter\Options\LanguageCodeOptions();
    $options->setOutput('native');
    $converter = new \Conversio\Conversion($adapter);
    $converter->setAdapterOptions($options);
?>

<?php 
    if($file){
?>

<div class="mh-caption-search">
    <div class="mh-transcript-hide-bp2">
        <div class="title wdn-sans-serif wdn-icon-search">
            <h6 class="wdn-sans-serif">Searchable Transcript</h6>
            <div class="wdn-icon-info mh-tooltip italic">
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
                <?php 
                    $languageTracks = array_keys($tracks);
                    $i = 0;
                    foreach ($languageTracks as $languageTrack) {
                       echo "<option value='".$languageTrack."'>".$converter->filter($languageTrack)."</option>";
                       $i++;
                    }
                ?>
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
            <input type="text" class="mh-parse-caption"><div class="mh-caption-close"></div>
            <ul class="mh-transcript">
                <?php 
                    foreach ($file->getCues() as $line) {
                        echo '<li><a class="highlight" href="javascript:void(0);"><span>[';
                        echo ($line->getstart());
                        echo ']</span> ';
                        echo ($line->getText());
                        echo '</a></li>';
                    }
                ?>
            </ul>
        </div>
    </div>
    <div class="mh-transcript-show-bp2 mh-too-small-message">
        <h3>The screen size you are trying to search captions on it too small!</h3>
        <p>you can always <a href="<?php echo $context->getURL(); ?>" target="_blank">Jump over to MediaHub</a> and check it out there.</p>
    </div>
</div>

<?php 
    }
?>


