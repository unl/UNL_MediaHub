<?php 
    include 'languages.php';
    $file = false;
    try {
        $vtt = new Captioning\Format\WebvttFile();
        $tracks = $context->getTextTracks();
        var_dump($tracks);
        $file = $vtt->loadFromString(reset($tracks));
    }catch(Exception $err){
    }
?>

<?php 
    if($file){
?>

<div class="mh-caption-search">
    <div class="mh-hide-bp2">
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
            <?php 
                $languageTracks = array_keys($tracks);
            ?>
            <select id="mh-language-select">
                <?php 
                    $languageTracks = array_keys($tracks);

                    foreach ($languageTracks as $languageTrack) {
                       echo "<option value='".$languageTrack."'>".$languages[$languageTrack]."</option>";
                    }
                ?>
            </select>
            <button class="mh-caption-search-close caption-toggle" aria-label="Close Searchable Transcript">x</button>
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
    <div class="mh-show-bp2 mh-too-small-message">
        <h3>The screen size you are trying to search captions on it too small!</h3>
        <p>you can always <a href="<?php echo $context->getURL(); ?>">Jump over to MediaHub</a> and check it out there.</p>
    </div>
</div>

<?php 
    }
?>


