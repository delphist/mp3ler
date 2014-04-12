<?php

/**
 * Пагинатор jquery mobile
 */
class Pager extends CLinkPager
{
    public function run()
    {
        $buttons=$this->createPageButtons();
        if(empty($buttons))
            return;

        if(count($buttons) > 1)
        {
            echo '<fieldset class="ui-grid-a">';

            echo '<div class="ui-block-a">'.$buttons[0].'</div>';
            echo '<div class="ui-block-b">'.$buttons[1].'</div>';

            echo '</fieldset>';
        }
        else
        {
            echo $buttons[0];
        }
    }

    protected function createPageButtons()
    {
        if(($pageCount=$this->getPageCount())<=1)
            return array();

        list($beginPage,$endPage)=$this->getPageRange();
        $currentPage=$this->getCurrentPage(false); // currentPage is calculated in getPageRange()
        $buttons=array();

        // prev page
        if(($page=$currentPage-1)<0)
            $page=0;
        else
            $buttons[]=$this->createPageButton($this->prevPageLabel,$page,$this->previousPageCssClass,$currentPage<=0,false);

        // next page
        $page=$currentPage+1;

        if($page <= $endPage)
            $buttons[]=$this->createPageButton($this->nextPageLabel,$page,$this->nextPageCssClass,$currentPage>=$pageCount-1,false);

        return $buttons;
    }

    protected function createPageButton($label,$page,$class,$hidden,$selected)
    {
        return CHtml::link($label, $this->createPageUrl($page), array('class' => 'ui-btn ui-mini'));
    }
}