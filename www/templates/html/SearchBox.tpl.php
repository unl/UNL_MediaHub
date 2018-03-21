<?php
/**
 * @var $context UNL_MediaHub_Filter
 */

$baseUrl = UNL_MediaHub_Controller::getURL();
?>
<form method="get" action="<?php echo $baseUrl ?>search/" class="mh-app-search">
    <label for="q_app">Search MediaHub</label>
    <div class="wdn-input-group">
        <input id="q_app" name="q" type="text" required<?php if ($context->getValue()): ?> value="<?php echo UNL_MediaHub::escape($context->getValue()) ?>"<?php endif ?> />
        <span class="wdn-input-group-btn ">
            <button type="submit"><span class="wdn-icon-search" aria-hidden="true"></span><span class="wdn-text-hidden">Search</span></button>
        </span>
    </div>
</form>
