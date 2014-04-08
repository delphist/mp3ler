<?php

class TagBar extends CWidget
{
    public $count = 15;

    public function run()
    {
        $max_id = (int) Yii::app()->db->createCommand()
            ->select('id')
            ->from('query_queue')
            ->order('id DESC')
            ->limit(1)
            ->queryScalar();

        $position = rand(1, max($max_id - $this->count, 1));

        $queue_ids = Yii::app()->db->createCommand()
            ->selectDistinct('query_id')
            ->from('query_queue')
            ->where('id >= '.$position)
            ->limit($this->count)
            ->queryColumn();

        if(count($queue_ids) > 0)
        {
            $queries = Query::model()->findAll(array(
                'condition' => 'id IN ('.implode($queue_ids, ', ').')'
            ));

            shuffle($queries);

            $this->render('tagBar', array(
                'queries' => $queries,
            ));
        }
    }
}