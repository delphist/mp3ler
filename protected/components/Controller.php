<?php

class Controller extends CController
{
    public $layout = '//layouts/main';

    function init()
    {
        parent::init();

        $possible_values = array(
            'en', 'ru', 'az', 'tr', 'ge'
        );

        if(Yii::app()->request->cookies->contains('dil') && in_array(Yii::app()->request->cookies['dil']->value, $possible_values))
        {
            Yii::app()->setLanguage(Yii::app()->request->cookies['dil']->value);
        }
    }
}