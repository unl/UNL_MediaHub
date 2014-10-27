<script type="text/javascript">
    //<![CDATA[ 
    WDN.jQuery(document).ready(function ($) { 
        WDN.loadCSS('/wdn/templates_3.0/scripts/plugins/ui/jquery-ui.css'); 
        WDN.loadCSS('/wdn/templates_3.0/scripts/plugins/ui/ui.datepicker.css'); 
        WDN.loadJS('/wdn/templates_3.0/scripts/plugins/ui/jQuery.ui.js', function () {
            $('#media_creation_date').datepicker({ dateFormat: 'yy-mm-dd' }); 
        }); 
    }); 
    //]]>
</script>

<li>
    <label for="media_creation_date" class="element">Media Creation Date<span class="helper">The date that the media was created.</span></label>
    <div class="element">
        <input name="UNL_MediaHub_Feed_Media_NamespacedElements_mediahub[2][element]" type="hidden" value="media_creation_date"/>
        <input id="media_creation_date" name="UNL_MediaHub_Feed_Media_NamespacedElements_mediahub[2][value]" type="text" value="<?php echo getFieldValue($context, 'mediahub', 'media_creation_date'); ?>"/>
    </div>
</li>