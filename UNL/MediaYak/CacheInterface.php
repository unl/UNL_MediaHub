<?php

interface UNL_MediaYak_CacheInterface
{
    public function get($key);
    public function save($data, $key);
}
