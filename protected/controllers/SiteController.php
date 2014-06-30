<?php

class SiteController extends Controller
{
    public function filters()
    {
        return array(
            'domainControl',
            'transitionControl -error',
            'languageControl',
            'languageRedirect -error',
            'counter -error'
        );
    }

	public function actionIndex()
	{
        $criteria = new CDbCriteria(array(
            'order' => 'position ASC',
            'condition' => 'position IS NOT NULL',
            'limit' => 12,
        ));

        $tracks = Billboard::model()->cache(60)->findAll($criteria);

        $this->render('index', array(
            'tracks' => $tracks,
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