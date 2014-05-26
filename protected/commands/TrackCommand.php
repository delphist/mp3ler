<?php

class TrackCommand extends CConsoleCommand
{
    /**
     * Удаляет дубликаты треков в базе данных
     */
    public function actionRemoveDuplicates()
    {
        while(TRUE)
        {
            $command = Yii::app()->db->createCommand('SELECT track.file, track.id FROM track GROUP BY file HAVING COUNT(*) > 1 ORDER BY COUNT(*) LIMIT 500');
            $reader = $command->query();
            $break = TRUE;

            while(($row = $reader->read()) !== FALSE)
            {
                $break = FALSE;

                echo Yii::app()->db->createCommand('DELETE FROM track WHERE id<>:id AND file=:file')->execute(array(
                    ':id' => (int) $row['id'],
                    ':file' => $row['file']
                ));

                echo "\n";
            }

            if($break)
            {
                break;
            }
        }
    }
}