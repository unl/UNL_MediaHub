<?php
class UNL_MediaHub_Subscription_Form
{
    public $action;

    public $filters;

    public $subscription;

    function __construct($options = array())
    {
        $this->filters = new UNL_MediaHub_Subscription_AvailableFilters($options);
    }
}