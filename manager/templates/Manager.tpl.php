<?php

$page = UNL_Templates::factory('Fixed');
$page->doctitle = '<title>UNL | Media Hub | Manager</title>';
$page->leftRandomPromo = '';

$page->navlinks = '
<ul>
<li>My Channels</li>
</ul>';

$page->maincontentarea = UNL_MediaYak_OutputController::display($this->output, true);

echo $page;
