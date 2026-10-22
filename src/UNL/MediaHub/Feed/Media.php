<?php

class UNL_MediaHub_Feed_Media extends UNL_MediaHub_Models_BaseFeedHasMedia
{
    public function __serialize(): array
    {
        return parent::__serialize();
    }

    public function __unserialize(array $data): void
    {
        parent::__unserialize($data);
    }
}
