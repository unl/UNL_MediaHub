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
    require(['jquery'], function($) {
        $(document).ready(function() {
            //water info.
            if ($('#water_cfs').val() == '') {
              $('#water_cfs_form').hide();
            }

            if ($('#water_maf').val() == '') {
                $('#water_maf_form').hide();
            }

            $('#show_cfs').click(function() {
              $('#water_cfs_form').show();
              $('#water_maf_form').hide();
              return false;
            });

            $('#show_maf').click(function() {
              $('#water_maf_form').show();
              $('#water_cfs_form').hide();
              return false;
            });

            //Only one of water_maf or water_cfs should be filled out at once.
            $('#water_maf').change(function() {
                $('#water_cfs').val('');
            });

            $('#water_cfs').change(function() {
                $('#water_maf').val('');
            });
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