<?php

class ConsoleController extends Controller
{
    public $layout = '//layouts/console';

    public function filters()
    {
        return array(
            'languageControl', 'languageRedirect', 'accessControl', 'transitionChart +partners, partner'
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'users' => array('@'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        $tracks_count = Track::model()->count();
        $queries_count = Query::model()->count();

        $this->render('index', array(
            'tracks_count' => $tracks_count,
            'queries_count' => $queries_count,
        ));
    }

    public function actionAccounts()
    {
        $all_accounts = VkAccount::model()->count();

        $accounts = VkAccount::model()->findAll();

        $alive_accounts = VkAccount::model()->countByAttributes(array(
            'is_alive'=> 1
        ));

        $errors = array();

        foreach($accounts as $account)
        {
            if($account->vkError != NULL && $account->vkError->error_code)
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

        $this->render('accounts', array(
            'accounts' => $accounts,
            'all_accounts' => $all_accounts,
            'errors' => $errors,
            'alive_accounts' => $alive_accounts,
        ));
    }

    public function actionPartners()
    {
        $criteria = new CDbCriteria(array(
            'order' => 'id DESC',
            'condition' => '`group`=:group',
            'params' => array('group' => 'partner')
        ));

        $pages = new CPagination(User::model()->count($criteria));
        $pages->pageSize = 10;
        $pages->applyLimit($criteria);

        $partners = User::model()->findAll($criteria);

        $this->render('partners', array(
            'partners' => $partners,
            'pages' => $pages,
        ));
    }

    public function actionPartner($id)
    {
        $partner = User::model()->findByPk($id);

        if($partner === NULL)
        {
            throw new CHttpException(404);
        }

        $criteria = new CDbCriteria(array(
            'order' => 'id DESC',
            'condition' => '`user_id`=:partnerId',
            'params' => array('partnerId' => $partner->id)
        ));

        $pages = new CPagination(Payout::model()->count($criteria));
        $pages->pageSize = 10;
        $pages->applyLimit($criteria);

        $payouts = Payout::model()->findAll($criteria);

        $this->render('partner', array(
            'partner' => $partner,
            'payouts' => $payouts
        ));
    }

    public function actionPayout($id = NULL)
    {
        if($id != NULL)
        {
            $partner = User::model()->findByPk($id);
        }

        $payout = $partner->createPayout();

        $this->render('payout', array(
            'payout' => $payout
        ));
    }
}