<?php

class ConsoleController extends Controller
{
    public $layout = '//layouts/console';

    public function actionIndex()
    {
        $all_accounts = VkAccount::model()->count();

        $alive_accounts = VkAccount::model()->countByAttributes(array(
            'is_alive'=> 1
        ));

        $this->render('index', array(
            'all_accounts' => $all_accounts,
            'alive_accounts' => $alive_accounts
        ));
    }

    public function actionLogin()
    {
        $this->render('login');
    }
}