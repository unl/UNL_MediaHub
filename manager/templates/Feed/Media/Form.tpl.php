<h1>Add media</h1>
<form action="?view=feed" method="post" name="media" id="media" enctype="multipart/form-data">
    <div style="display: none;">
        <input type="hidden" id="feed_id" name="feed_id" value="<?php echo (int)$_GET['feed_id']; ?>" />
        <input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="feed_media" />
        <input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="67108864" />
    </div>

    <fieldset id="existing_media">
        <legend>Existing Media</legend>
        <ol>
            <li><label for="url" class="element">URL</label><div class="element"><input id="url" name="url" type="text" size="60" /></div></li>
            <li><label for="title" class="element">Title</label><div class="element"><input id="title" name="title" type="text" size="60" /></div></li>
            <li>
                <label for="description" class="element">Description</label>
                <div class="element"><textarea id="description" name="description" rows="5" cols="60"></textarea></div>
            </li>

            <li><label for="submit_existing" class="element">&nbsp;</label><div class="element"><input id="submit_existing" name="submit_existing" value="Save" type="submit" /></div></li>
        </ol>
    </fieldset>
    <fieldset id="itunes_header">

        <legend>iTunes Options <a href="#" onclick="document.getElementById('itunes_elements').style.display='block'; return false;">Show</a></legend>
        <ol style="display:none;" id="itunes_elements">
            <?php
            $itunes = new UNL_MediaYak_Feed_Media_NamespacedElements_itunes();
            foreach ($itunes->getItemElements() as $element) {
                $value = '';
                $label = ucwords($element);
                echo "<li><label for='itunes_$element' class='element'>$label</label><div class='element'><input id='itunes_$element' name='itunes_$element' type='text' value='$value' size='55' /></div></li>"; 
            }
            ?>
            <li><label for="itunes_submit" class="element">&nbsp;</label><div class="element"><input id="itunes_submit" name="submit" value="Save" type="submit" /></div></li>
        </ol>
    </fieldset>
    <fieldset id="new_media">
        <legend>Upload new media</legend>
        <ol>
            <li><label for="file_upload" class="element">File</label><div class="element"><input id="file_upload" name="file_upload" type="file" /></div></li>
            <li><label for="qf_4f7d51" class="element">What would you like to do with this file?</label><div class="element"><input name="upload_target" type="radio" id="qf_4f7d51" /><label for="qf_4f7d51">Encode it</label></div></li>

            <li><label for="qf_a38a81" class="element">&nbsp;</label><div class="element"><input name="upload_target" type="radio" id="qf_a38a81" /><label for="qf_a38a81">Go Live</label></div></li>
            <li><label for="submit_new" class="element">&nbsp;</label><div class="element"><input id="submit_new" name="submit_new" value="Save" type="submit" /></div></li>
        </ol>
    </fieldset>
</form>