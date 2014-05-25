<?php

class VkCommand extends CConsoleCommand
{
    /**
     * Импортирует данные об аккаунтах вконтакте
     * Файл должен иметь следующий синтаксис:
     *
     * id_вконтакте_1:id_приложения_1
     * id_вконтакте_2:id_приложения_2
     * ...
     *
     * @param $filename путь до файла
     */
    public function actionImport($filename)
    {
        $file = file($filename);

        foreach($file as $line)
        {
            $account = new VkAccount;
            list($account->vk_id, $account->app_id) = explode(';', trim($line));
            $account->save();
        }
    }

    /**
     * Проверяет аккаунт, совершая поиск музыки
     * и печатая результат в stdout
     *
     * @param $account_id id аккаунта вк
     * @param $query текст запроса
     */
    public function actionCheck($account_id, $query = NULL)
    {
        $account = VkAccount::model()->findByPk($account_id);

        if($query === NULL)
        {
            $query = 'eminem';
        }

        $api = new VkApi;
        $response = $api->execute_account($account, 'audio.search', array(
            'q' => $query,
            'auto_complete' => 1,
            'sort' => 2,
            'count' => 10
        ));

        print_r($response);

        print_r($account);
    }

    /**
     * Сбрасывает статус всех аккаунтов
     */
    public function actionResetAlive()
    {
        VkAccount::model()->updateAll(array('is_alive' => 1, 'error_response' => ''));
    }
}