<?php
UNL_MediaYak_OutputController::setOutputTemplate('UNL_MediaYak_Media', 'MediaJSON');
echo UNL_MediaYak_OutputController::display($this->output, true);