<?php
class UNL_MediaYak_Subscription_Form
{
    public $action;

    public $filters;

    public $subscription;

    function __construct($options = array())
    {
        $this->filters = new UNL_MediaYak_Subscription_AvailableFilters($options);
    }
}