<?php

/**
 * Контроллер для партнеров
 */
class PartnerController extends Controller
{
    public $layout = '//layouts/console';

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
        $this->render('payouts', array(

        ));
    }

    public function actionSettings()
    {
        $this->render('settings', array(

        ));
    }
}