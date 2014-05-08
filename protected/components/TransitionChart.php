<?php

class TransitionChart extends CWidget
{
    public $periodData;
    public $options;

    public function run()
    {
        $data = Yii::app()->transitionStatistics->show($this->periodData, $this->options);
        $labels = array_keys($data);

        $this->render('transitionChart', array(
            'data' => $data,
            'labels' => $labels,
        ));
    }
}