<?php
/**
 * @var $context UNL_MediaHub_Filter
 */

$baseUrl = UNL_MediaHub_Controller::getURL();
?>
<form method="get" action="<?php echo $baseUrl ?>search/" class="mh-app-search">
    <label for="q_app">Search MediaHub</label>
    <div class="wdn-input-group">
        <input id="q_app" name="q" type="text" required<?php if ($context->getValue()): ?> value="<?php echo htmlentities($context->getValue(), ENT_HTML5|ENT_QUOTES) ?>"<?php endif ?> />
        <span class="wdn-input-group-btn ">
            <button type="submit" class="wdn-icon-search"></button>
        </span>
    </div>
</form>
