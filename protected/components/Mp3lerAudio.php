<?php

/**
 * Класс поиска музыки среди файлов старой версии сайта
 */
class Mp3lerAudio extends Audio
{
    public function results()
    {
        /**
         * Составляем имя файла
         */
        $filename = Yii::app()->params['mp3ler_path'].'/data4/'.md5($this->query).'_10.dat';

        $results = new Results;

        /**
         * Ищем файл в старой папке и сохраняем результаты из него
         */
        if(is_file($filename))
        {
            $data = file($filename);
            foreach($data as $line)
            {
                $line = explode('~|~', $line);

                $results[] = array(
                    'id' => $line[3],
                    'type' => 'm3',
                    'artist_title' => $this->decode_string($line[0]),
                    'title' => $this->decode_string($line[1]),
                    'url' => $this->decode_string($line[6]),
                );
            }

            $filename = $mp3ler_path.'/data4/'.md5($this->query).'_n.dat';

            if(is_file($filename))
            {
                $data = file_get_contents($filename);
                $results->count = $data;
            }
            else
            {
                $results->count = count($results);
            }
        }

        return $results;
    }
}