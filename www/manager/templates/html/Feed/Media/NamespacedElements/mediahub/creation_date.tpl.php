<script type="text/javascript">
    WDN.initializePlugin('jqueryui', [function () {
        var $ = require('jquery');
        $('#media_creation_date').datepicker({ dateFormat: 'yy-mm-dd' });
    }]);
</script>


<label for="media_creation_date" class="element">Media Creation Date<span class="helper">The date that the media was created.</span></label>
<div class="element">
    <input name="UNL_MediaHub_Feed_Media_NamespacedElements_mediahub[2][element]" type="hidden" value="media_creation_date"/>
    <input id="media_creation_date" name="UNL_MediaHub_Feed_Media_NamespacedElements_mediahub[2][value]" type="text" value="<?php echo getFieldValue($context, 'mediahub', 'media_creation_date'); ?>"/>
</div>