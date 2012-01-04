<label for="water_flow_type" class="element">Water Quantity Type:</label>
<div class="element">
   <a href='#' id='show_maf'>Volume</a> or <a href='#' id='show_cfs'>Flow?</a>
</div>
<ol>
    <li id='water_cfs_form'>
        <label for="water_cfs" class="element">Flow rate of water in cubic feet per second (cfs)</label>
        <div class="element">
            <input name="UNL_MediaHub_Feed_Media_NamespacedElements_mediahub[0][element]" type="hidden" value="water_cfs"/>
            <input id="water_cfs" name="UNL_MediaHub_Feed_Media_NamespacedElements_mediahub[0][value]" type="text" value="<?php echo getFieldValue($context, 'mediahub', 'water_cfs'); ?>"/>
        </div>
    </li>
    <li id='water_maf_form'>
        <label for="water_maf" class="element">Volume of water in acre-feet (af)</label>
        <div class="element">
            <input name="UNL_MediaHub_Feed_Media_NamespacedElements_mediahub[1][element]" type="hidden" value="water_af"/>
            <input id="water_maf" name="UNL_MediaHub_Feed_Media_NamespacedElements_mediahub[1][value]" type="text" value="<?php echo getFieldValue($context, 'mediahub', 'water_af'); ?>"/>
        </div>
    </li>
</ol>