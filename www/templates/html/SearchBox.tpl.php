<?php
/**
 * @var $context UNL_MediaHub_Filter
 */

$baseUrl = UNL_MediaHub_Controller::getURL();
?>
<form method="get" action="<?php echo $baseUrl ?>search/" class="dcf-form mh-app-search">
    <label for="q_app">Search MediaHub</label>
    <div class="dcf-input-group">
        <input id="q_app" name="q" type="text" required<?php if ($context->getValue()): ?> value="<?php echo UNL_MediaHub::escape($context->getValue()) ?>"<?php endif ?> />
        <button class="dcf-btn dcf-btn-primary" type="submit" aria-label="Search for Media"><?php echo \UNL\Templates\Icons::get(\UNL\Templates\Icons::ICON_SEARCH, '{"size": 6}'); ?><span class="dcf-sr-only">Search</span></button>
    </div>
</form>
