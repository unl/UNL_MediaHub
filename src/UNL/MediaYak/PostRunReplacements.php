<?php

interface UNL_MediaYak_PostRunReplacements
{
    static function setReplacementData($field, $data);
    public function postRun($data);
}

?>