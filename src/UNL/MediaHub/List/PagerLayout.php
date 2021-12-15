<?php
class UNL_MediaHub_List_PagerLayout extends Doctrine_Pager_Layout
{
    public function display($options = array(), $return = false)
    {
        $pager = $this->getPager();

        if (!$pager->haveToPaginate()) {
            return '';
        }

        $this->setTemplate('<li><a {%linkclass} href="{%url}">{%page}</a></li>');
        $this->setSelectedTemplate('<li><span class="dcf-pagination-selected">{%page}</span></li>');

        $str = '<nav class="dcf-pagination dcf-txt-center"><ol class="dcf-list-bare dcf-list-inline">';

        if ($pager->getPage() !== 1) {
            // Previous page
            $this->addMaskReplacement('linkclass', 'class="dcf-pagination-prev"', true);
            $this->addMaskReplacement('page', 'Prev', true);
            $options['page_number'] = $pager->getPreviousPage();
            $str .= $this->processPage($options);
        }

        // Pages listing
        $this->addMaskReplacement('linkclass', '', true);
        $this->removeMaskReplacement('page');
        $str .= parent::display($options, true);

        if ($pager->getPage() != $pager->getNextPage()) {
            // Next page
            $this->addMaskReplacement('linkclass', 'class="dcf-pagination-next"', true);
            $this->addMaskReplacement('page', 'Next', true);
            $options['page_number'] = $pager->getNextPage();
            $str .= $this->processPage($options);
        }

        $str .= '</ol></nav>';

        // Possible wish to return value instead of print it on screen
        if ($return) {
            return $str;
        }

        echo $str;
    }
}