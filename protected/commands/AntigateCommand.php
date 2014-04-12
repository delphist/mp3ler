<?php

class AntigateCommand extends CConsoleCommand
{
    /**
     * Циклически проверяет статусы аккаунтов вконтакте,
     * при появлении каптчи отправляет ее в очередь на распознание
     * и так же автоматически проверяет статус ее решения, сохраняя
     * эту информацию в аккаунт
     *
     * Эта команда предназначена для запуска в виде daemon'а
     * с ведением лог-файла
     */
    public function actionSolve()
    {
        $api = new VkApi;

        while(TRUE)
        {
            $accounts = VkAccount::model()->findAll(array(
                'condition' => 'is_captcha_request=1 AND is_captcha_response=0',
            ));

            foreach($accounts as $account)
            {
                if(isset($account->captcha_request['url']))
                {
                    echo 'Request captcha for '.$account->id."\n";
                }
                elseif(isset($account->captcha_request['solve_id']))
                {
                    echo 'Get captcha response for '.$account->id."\n";
                }

                $api->solve_captcha($account);
            }

            sleep(5);
        }
    }
}