<?php
//Only show if this media is part of the Water Media channel
if (!$water_media = UNL_MediaHub_Feed::getById(319)) {
    return;
}
if (!$water_media->hasMedia($context->media)) {
    return;
}
?>

<?php
$page->addScriptDeclaration("
    //<![CDATA[ 
    document.addEventListener('DOMContentLoaded', function () {
        const waterCfs = document.getElementById('water_cfs');
        const waterMaf = document.getElementById('water_maf');
        const cfsForm = document.getElementById('water_cfs_form');
        const mafForm = document.getElementById('water_maf_form');
        const showCfs = document.getElementById('show_cfs');
        const showMaf = document.getElementById('show_maf');

        //water info.
        if (waterCfs.value === '') {
            cfsForm.style.display = 'none';
        }

        if (waterMaf.value === '') {
            mafForm.style.display = 'none';
        }

        showCfs.addEventListener('click', function (event) {
            event.preventDefault();
            cfsForm.style.display = '';
            mafForm.style.display = 'none';
        });

        showMaf.addEventListener('click', function (event) {
            event.preventDefault();
            mafForm.style.display = '';
            cfsForm.style.display = 'none';
        });

        //Only one of water_maf or water_cfs should be filled out at once.
        waterMaf.addEventListener('change', function () {
            waterCfs.value = '';
        });

        waterCfs.addEventListener('change', function () {
            waterMaf.value = '';
        });
    });
    //]]>");
?>

<fieldset>
    <legend class="dcf-legend">Water Flow</legend>
    <div class="element">
       <a href='#' id='show_maf'>Volume</a> or <a href='#' id='show_cfs'>Flow?</a>
    </div>
    <ol>
        <li id='water_cfs_form'>
            <label for="water_cfs">Flow rate of water in cubic feet per second (cfs)</label>
            <div class="element">
                <input name="UNL_MediaHub_Feed_Media_NamespacedElements_mediahub[0][element]" type="hidden" value="water_cfs"/>
                <input id="water_cfs" name="UNL_MediaHub_Feed_Media_NamespacedElements_mediahub[0][value]" type="text" value="<?php echo getFieldValue($context, 'mediahub', 'water_cfs'); ?>"/>
            </div>
        </li>
        <li id='water_maf_form'>
            <label for="water_maf">Volume of water in acre-feet (af)</label>
            <div class="element">
                <input name="UNL_MediaHub_Feed_Media_NamespacedElements_mediahub[1][element]" type="hidden" value="water_af"/>
                <input id="water_maf" name="UNL_MediaHub_Feed_Media_NamespacedElements_mediahub[1][value]" type="text" value="<?php echo getFieldValue($context, 'mediahub', 'water_af'); ?>"/>
            </div>
        </li>
    </ol>
</fieldset>