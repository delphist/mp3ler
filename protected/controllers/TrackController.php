<?php

class TrackController extends Controller
{
    public function filters()
    {
        return array(
            'languageControl',
        );
    }

    /**
     * @var Track обьект трека
     */
    protected $track;

    /**
     * Скачивание файла с сервера
     *
     * @param $filename string имя файла
     * @param $data string
     */
    public function actionDownload($filename, $data)
    {
        /**
         * При разрыве коннекта с клиентом файл должен
         * докачаться и сохраниться в базу
         */
        ignore_user_abort(TRUE);
        set_time_limit(60 * 2);
        ini_set('memory_limit', '256M');

        /**
         * Достаем информацию
         */
        $data = json_decode(base64_decode($data), true);

        /**
         * Ищем трек по внешнему id в нашей базе, если
         * он уже был хоть раз скачан, иначе создаем новый
         */
        $this->track = Track::model()->findByData($data);

        if($this->track === NULL)
        {
            $this->track = new Track;
            $this->track->data = $data;
        }

        if($this->track->downloadable && ! $this->track->isDownloaded)
        {
            /**
             * Если файл нужно скачивать, но при этом он еще
             * не скачан либо куда-то исчез с диска, то качаем
             * его заново и сразу же отдаем в браузер
             */

            $result = FALSE;
            try
            {
                $result = $this->track->download(
                    NULL,
                    array($this, '_callback_body')
                );
            }
            catch(Exception $e)
            {
                if($e->getMessage() == 'Http code 404')
                {
                    /**
                     * Если у вк на файл 404 то дальше будем пробовать найти другой трек
                     */
                    $result = FALSE;
                }
                else
                {
                    throw $e;
                }
            }

            if($result)
            {
                /**
                 * При удачной загрузке сохраняем в базу и закрываем коннект
                 */
                $this->track->save();

                Yii::app()->end();
            }
            else
            {
                /**
                 * Если загрузка не получилась, то находим в VK
                 * трек с таким же названием и пытаемся скачать его
                 */
                $title = $this->track->artist_title.' - '.$this->track->title;
                $vk_audio = new VkAudio($title);
                $results = $vk_audio->results();

                /**
                 * Проверяем все результаты на точное совпадение с именем
                 * трека
                 */
                $found_result = NULL;
                foreach($results as $result)
                {
                    if($result['artist_title'].' - '.$result['title'] == $title)
                    {
                        $found_result = $result;
                        break;
                    }
                }

                /**
                 * Если точных совпадений нет берем первое
                 */
                if($found_result === NULL)
                {
                    reset($results);
                    $found_result = current($results);
                }

                $found_track = Track::model()->findByData($found_result);
                if($found_track !== NULL)
                {
                    /**
                     * Если такой трек уже есть в базе, то редиректим на него
                     */
                    $this->redirect($this->createTrackDownloadUrl($found_track));
                }
                else
                {
                    /**
                     * Если же такого трека нет в базе, то качаем прямо в этом процессе
                     */
                    $this->track = new Track;
                    $this->track->data = $data;

                    $this->track->download(
                        NULL,
                        array($this, '_callback_body')
                    );

                    $this->track->save();
                }
            }

        }
        else
        {
            $this->_send_headers(filesize($this->track->filePath), $this->track->filename);

            /**
             * Увеличиваем счетчик скачиваний
             */
            $this->track->saveCounters(array(
                'downloads_count' => 1
            ));

            if(YII_DEBUG)
            {
                /**
                 * Локально читаем файл и отдаем через php
                 */
                $this->_flush();

                readfile($this->track->filePath);
            }
            else
            {
                /**
                 * На продакшене посылаем заголовок чтобы файлом занимался nginx
                 */
                header('X-Accel-Redirect: '.$this->track->fileUrl);
            }

            Yii::app()->end();
        }
    }

    /**
     * Страница самых загружаемых треков
     */
    public function actionTop()
    {
        $tracks = Track::model()->cache(60)->findAll(array(
            'order' => 'downloads_count DESC',
            'limit' => 10,
        ));

        $this->render('top', array(
            'tracks' => $tracks,
        ));
    }

    /**
     * Коллбек, срабатывающий при чтении каждой части файла CURL'ом
     *
     * @param $curl
     * @param $string
     */
    public function _callback_body($curl, $string)
    {
        /**
         * Если заголовки еще не были отправлены,
         * то составляем и отправляем их
         */
        if( ! headers_sent())
        {
            $this->_send_headers(curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD), $this->track->filename);

            ob_start();
        }

        /**
         * Посылаем часть файла браузеру
         */
        echo $string;

        $this->_flush();
    }

    protected function _send_headers($length, $filename)
    {
        header('Content-Type: audio/mpeg');
        header('Content-Length: '.$length);
        header('Content-Transfer-Encoding: binary');
        header('Content-Disposition: attachment; filename="'.addslashes($filename).'"');
        header('Cache-Control: no-cache');
        header('Connection: close');
        header('Content-Transfer-Encoding: chunked');
    }

    protected function _flush()
    {
        /**
         * http://www.php.net/manual/ru/function.ob-flush.php
         */
        ob_end_flush();
        ob_flush();
        flush();
        ob_start();
    }
}