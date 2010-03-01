<?php
class UNL_MediaYak_List_PagerLayout extends Doctrine_Pager_Layout
{
public function display($options = array(), $return = false)
    {
        $pager = $this->getPager();

        $this->setTemplate('<li><a href="{%url}" title="Go to page {%page}">{%page}</a></li>');
        $this->setSelectedTemplate('<li class="selected">{%page}</li>');

        $str = '<ul class="wdn_pagination">';

        // Previous page
        $this->addMaskReplacement('page', '&larr; prev', true);
        $options['page_number'] = $pager->getPreviousPage();
        $str .= $this->processPage($options);

        // Pages listing
        $this->removeMaskReplacement('page');
        $str .= parent::display($options, true);

        // Next page
        $this->addMaskReplacement('page', 'next &rarr;', true);
        $options['page_number'] = $pager->getNextPage();
        $str .= $this->processPage($options);

        $str .= '</ul>';

        // Possible wish to return value instead of print it on screen
        if ($return) {
            return $str;
        }

        echo $str;
    }
}