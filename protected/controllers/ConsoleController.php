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

        $this->render('index', array(
            'all_accounts' => $all_accounts,
            'alive_accounts' => $alive_accounts,
            'tracks_count' => $tracks_count,
            'queries_count' => $queries_count,
        ));
    }

    public function actionAccounts()
    {
        $accounts = VkAccount::model()->findAll();
        $errors = array();

        foreach($accounts as $account)
        {
            if($account->errorCode)
            {
                if( ! isset($errors[$account->errorCode]))
                {
                    $errors[$account->errorCode] = array(
                        'code' => $account->error->error_code,
                        'msg' => $account->error->error_msg,
                        'count' => 0,
                    );
                }

                $errors[$account->error_code]['count']++;
            }
        }

        $this->render('accounts', array(
            'errors' => $errors,
        ));
    }

    public function actionLogin()
    {
        $this->render('login');
    }
}