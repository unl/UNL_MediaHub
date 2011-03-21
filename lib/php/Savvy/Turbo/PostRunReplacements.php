<?php

interface Savvy_Turbo_PostRunReplacements
{
    public function setReplacementData($field, $data);
    public function postRun($data);
}
