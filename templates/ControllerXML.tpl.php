<?php
UNL_MediaYak_OutputController::setOutputTemplate('UNL_MediaYak_FeedAndMedia', 'FeedAndMediaXML');
echo UNL_MediaYak_OutputController::display($this->output, true);