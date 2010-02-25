<?php
UNL_MediaYak_OutputController::setOutputTemplate('UNL_MediaYak_Media', 'MediaJSON');
echo $savvy->render($context->output);