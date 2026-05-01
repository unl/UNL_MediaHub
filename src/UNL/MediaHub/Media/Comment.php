<?php
class UNL_MediaHub_Media_Comment extends UNL_MediaHub_Models_BaseMediaComment
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