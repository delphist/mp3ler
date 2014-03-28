<?php

class SiteController extends Controller
{
	public function actionIndex()
	{
        $dataProvider = new CActiveDataProvider('Query');

        $this->render('index',array(
            'dataProvider' => $dataProvider,
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