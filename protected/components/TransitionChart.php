<?php

/**
 * Виджет показа графика переходов
 */
class TransitionChart extends CWidget
{
    /**
     * @var array Массив с данными о периоде показа
     */
    public $periodData;

    /**
     * @var array Опции выборки данных (см. TransitionStatistics)
     */
    public $options;

    /**
     * @var array Какие клики выводить на график (countable - засчитанные, notcountable - незасчитанные)
     */
    public $chartLists = array('countable');

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

    public function getCountable()
    {
        return Yii::app()->transitionStatistics->show($this->periodData, array_merge($this->options, array(
            'countable' => TRUE,
        )));
    }

    public function getNotcountable()
    {
        return Yii::app()->transitionStatistics->show($this->periodData, array_merge($this->options, array(
            'countable' => FALSE,
        )));
    }
}