<?php

interface UNL_MediaYak_MediaList_Filter
{
    function apply(Doctrine_Query &$query);
    function getLabel();
    function getType();
    function getValue();
    function __toString();
}
