<?php

class UNL_MediaHub_RevOrder extends UNL_MediaHub_Models_BaseRevOrder
{
    /**
     * called before an item is inserted to the database
     *
     * @param $event
     *
     * @return void
     */
    public function preInsert($event)
    {
        $this->datecreated = date('Y-m-d H:i:s');
    }
}
