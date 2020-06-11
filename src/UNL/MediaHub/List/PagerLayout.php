<?php
class UNL_MediaHub_List_PagerLayout extends Doctrine_Pager_Layout
{
    public function display($options = array(), $return = false)
    {
        $pager = $this->getPager();

        if (!$pager->haveToPaginate()) {
            return '';
        }

        $this->setTemplate('<li><a href="{%url}" title="Go to page {%page}">{%page}</a></li>');
        $this->setSelectedTemplate('<li class="selected">{%page}</li>');

        $str = '<ul class="mh_pagination">';

        if ($pager->getPage() !== 1) {
            // Previous page
            $this->addMaskReplacement('page', '&larr; prev', true);
            $options['page_number'] = $pager->getPreviousPage();
            $str .= $this->processPage($options);
        }

        // Pages listing
        $this->removeMaskReplacement('page');
        $str .= parent::display($options, true);

        if ($pager->getPage() != $pager->getNextPage()) {
            // Next page
            $this->addMaskReplacement('page', 'next &rarr;', true);
            $options['page_number'] = $pager->getNextPage();
            $str .= $this->processPage($options);
        }

        $str .= '</ul>';

        // Possible wish to return value instead of print it on screen
        if ($return) {
            return $str;
        }

        echo $str;
    }
}