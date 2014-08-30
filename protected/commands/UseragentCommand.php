<?php

class UseragentCommand extends CConsoleCommand
{
    /**
     * Экспортирует список юзерагентов в .txt файл
     */
    public function actionExport()
    {
        $file = fopen('useragent.txt', 'w+');

        $command = Yii::app()->db->createCommand('SELECT * FROM useragent');
        $reader = $command->query();

        foreach($reader as $row)
        {
            fwrite($file, $row['title'].' --- '.$row['headers']."\r\n");
        }

        fclose($file);
    }
}