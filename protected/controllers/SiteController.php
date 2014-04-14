<?php

class SiteController extends Controller
{
    public function filters()
    {
        return array(
            'languageControl', 'languageRedirect -error'
        );
    }

	public function actionIndex()
	{
        $queue_ids = Yii::app()->db->cache(3, NULL, 2)
            ->createCommand()
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

    public function actionPartner($x)
    {
        $cookie = new CHttpCookie('dilxs', $x);
        $cookie->expire = time()+60*60*24*180;

        Yii::app()->request->cookies['dilxs'] = $cookie;

        $this->redirect('/', TRUE, 301);
    }

    public function actionPartnerInfo()
    {
        $this->render('partnerInfo');
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