<?php

class SiteController extends Controller
{
    public function filters()
    {
        return array(
            'languageControl',
        );
    }

	public function actionIndex()
	{
        $queue_ids = Yii::app()->db->createCommand()
            ->selectDistinct('query_id')
            ->from('query_queue')
            ->order('id DESC')
            ->limit(10)
            ->queryColumn();

        $queries = Query::model()->findAll(array(
            'condition' => 'id IN ('.implode($queue_ids, ', ').')',
            'order' => 'FIELD(id, '.implode($queue_ids, ', ').') ASC',
        ));

        $this->render('index', array(
            'queries' => $queries,
        ));
	}

	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
}