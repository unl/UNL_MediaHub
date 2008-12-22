<?php
UNL_MediaYak_OutputController::setOutputTemplate('UNL_MediaYak_Feed', 'FeedXML');
echo UNL_MediaYak_OutputController::display($this->output, true);