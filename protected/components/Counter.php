<?php

/**
 * Виджет показа графика переходов
 */
class Counter extends CWidget
{
    public function run()
    {
        $dataLists = array();

        foreach($this->chartLists as $listName)
        {
            $dataLists[$listName] = $this->$listName;
        }

        $labels = array_keys(current($dataLists));

        $this->render('transitionChart', array(
            'dataLists' => $dataLists,
            'labels' => $labels,
        ));
    }
}