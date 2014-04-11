<?php

class ConsoleController extends Controller
{
    public $layout = '//layouts/console';

    public function actionIndex()
    {
        $all_accounts = VkAccount::model()->count();

        $tracks_count = Track::model()->count();

        $queries_count = Query::model()->count();

        $alive_accounts = VkAccount::model()->countByAttributes(array(
            'is_alive'=> 1
        ));

        $accounts = VkAccount::model()->findAll();
        $errors = array();

        foreach($accounts as $account)
        {
            if($account->vkError->error_code)
            {
                if( ! isset($errors[$account->vkError->error_code]))
                {
                    $errors[$account->vkError->error_code] = array(
                        'code' => $account->vkError->error_code,
                        'msg' => $account->vkError->error_msg,
                        'count' => 0,
                    );
                }

                $errors[$account->vkError->error_code]['count']++;
            }
        }

        $this->render('index', array(
            'all_accounts' => $all_accounts,
            'alive_accounts' => $alive_accounts,
            'tracks_count' => $tracks_count,
            'errors' => $errors,
            'queries_count' => $queries_count,
        ));
    }

    public function actionAccounts()
    {
        $accounts = VkAccount::model()->findAll();

        $this->render('accounts', array(
            'accounts' => $accounts,
        ));
    }

    public function actionLogin()
    {
        $this->render('login');
    }
}