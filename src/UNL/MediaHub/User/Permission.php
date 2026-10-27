<?php
class UNL_MediaHub_User_Permission extends UNL_MediaHub_Models_BaseUserHasPermission
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