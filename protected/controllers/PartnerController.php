<?php

/**
 * Контроллер для партнеров
 */
class PartnerController extends Controller
{
    public $layout = '//layouts/console';

    public $defaultLanguage = 'en';

    public $possibleLanguages = array('en', 'ru');

    public $languageCookieName = 'partnerLanguage';

    public function filters()
    {
        return array(
            'languageControl', 'languageRedirect', 'accessControl', 'transitionChart +transitions'
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('transitions', 'payouts', 'settings', 'index'),
                'users' => array('@'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        $this->redirect($this->createUrl('partner/transitions'));
    }

    public function actionTransitions()
    {
        $this->render('transitions', array(
            'partner' => Yii::app()->user->model(),
        ));
    }

    public function actionPayouts()
    {
        $criteria = new CDbCriteria(array(
            'order' => 'id DESC',
            'condition' => '`user_id`=:partnerId',
            'params' => array('partnerId' => Yii::app()->user->model()->id)
        ));

        $pages = new CPagination(Payout::model()->count($criteria));
        $pages->pageSize = 10;
        $pages->applyLimit($criteria);

        $payouts = Payout::model()->findAll($criteria);

        $this->render('payouts', array(
            'payouts' => $payouts,
        ));
    }

    public function actionSettings()
    {
        $model = SettingsForm::model()->findByPk(Yii::app()->user->model()->id);

        if(isset($_POST['SettingsForm']))
        {
            $model->attributes=$_POST['SettingsForm'];

            if($model->save())
            {
                Yii::app()->user->setFlash('success', Yii::t('app', 'Settings has been successfully saved'));

                $this->redirect(array('partner/settings'));
            }
        }

        $this->render('settings', array(
            'model' => $model,
        ));
    }
}