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
}