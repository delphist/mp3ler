<?php

class TrackController extends Controller
{
    public function filters()
    {
        return array(
            'domainControl -download',
            'transitionControl',
            'languageControl',
            'languageRedirect -vkId -download',
            'collector',
            'counter -error'
        );
    }

    /**
     * @var Track обьект трека
     */
    protected $track;

    /**
     * Использовать ли streaming при первой загрузке файла?
     * Дело в том что это очень сильно плодит процессы, и в зависимости от
     * конфигурации сервера эту переменную нужно менять
     *
     * @var bool
     */
    protected $streaming = TRUE;

    /**
     * Скачивание файла с сервера
     *
     * @param $id идентификатор ссылки для скачивания
     * @param $filename string имя файла
     */
    public function actionDownload($filename, $id = '')
    {
        /**
         * При разрыве коннекта с клиентом файл должен
         * докачаться и сохраниться в базу
         */
        ignore_user_abort(TRUE);
        set_time_limit(60);
        ini_set('memory_limit', '256M');

        /**
         * Достаем трек
         */
        if( ! $id)
        {
            $this->redirectFilename($filename);
        }

        $this->track = $this->findTrackByKey($id);
        if($this->track === NULL)
        {
            $this->redirectFilename($filename);
        }

        if( ! $this->track->isDownloaded)
        {
            /**
             * Если файл нужно скачивать, но при этом он еще
             * не скачан либо куда-то исчез с диска, то качаем
             * его заново и сразу же отдаем в браузер
             */

            if(isset($this->track->data['server_id']) && (int) $this->track->data['server_id'] > 0)
            {
                /**
                 * Если трек не загружен и имеется информация о сервере, с которого нужно его скачивать, то перенаправляем на него
                 */
                $this->checkRedirectServer((int) $this->track->data['server_id']);
            }
            else
            {
                /**
                 * Иначе перенаправляем на стандартный сервер
                 */
                //$this->checkRedirectServer(2);
            }

            $result = FALSE;
            try
            {
                $result = $this->track->download(
                    NULL,
                    array($this, '_callback_body'),
                    array($this, '_callback_end')
                );
            }
            catch(Exception $e)
            {
                if($e->getMessage() == 'Http code 404')
                {
                    /**
                     * Если на файл 404 то дальше будем пробовать найти другой трек
                     */
                    $result = FALSE;
                }
                else
                {
                    throw $e;
                }
            }

            if( ! $result)
            {
                /**
                 * Если загрузка не получилась, то находим в VK
                 * трек с таким же названием и пытаемся скачать его
                 */
                $title = $this->normalizeQuery($this->track->artist_title.' - '.$this->track->title);
                $vk_audio = new VkAudio($title);
                $vk_audio->flush_cache = TRUE;
                $results = $vk_audio->results();

                if(count($results) == 0)
                {
                    throw new CHttpException(404, 'No new results for this file');
                }

                /**
                 * Проверяем все результаты на точное совпадение с именем
                 * трека
                 */
                $found_result = NULL;
                foreach($results as $result)
                {
                    if($this->normalizeQuery($result['artist_title'].' - '.$result['title']) == $title)
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
                    $found_result = $results[0];
                }

                $found_track = Track::model()->findByData($found_result);

                if($found_track !== NULL && $found_track->isDownloaded)
                {
                    if($found_track->id != $this->track->id)
                    {
                        /**
                         * Если такой трек уже есть в базе, то редиректим на него
                         */
                        $this->redirect($this->createTrackDownloadUrl($found_track));
                    }
                }

                /**
                 * Если же такого трека нет в базе, то качаем прямо в этом процессе
                 */
                $this->track->data = $found_result;

                /**
                 * Никаких редиректов не проверяем — т.к. мы установили flush_cache,
                 * то запрос был сделан точно с текущего сервера и качать файл нужно тоже с него
                 */

                try
                {
                    $this->track->download(
                        NULL,
                        array($this, '_callback_body'),
                        array($this, '_callback_end')
                    );
                }
                catch(Exception $e)
                {
                    if($e->getMessage() == 'Http code 404')
                    {
                        /**
                         * Теперь выдаем просто 404
                         */

                        throw new CHttpException(404, 'VK 404 on file ('.$this->track->data['url'].')');
                    }
                    else
                    {
                        throw $e;
                    }
                }

                Yii::app()->end();
            }

        }
        else
        {
            if( ! $this->track->downloadable)
            {
                throw new CHttpException(404, 'File is not downloadable');
            }

            /**
             * Редиректим на текущий сервер
             */
            $this->checkRedirectServer(Yii::app()->serverManager->currentServerId);

            $this->_send_headers(filesize($this->track->filePath), $this->track->filename);

            /**
             * Увеличиваем счетчик скачиваний
             */
            $this->track->saveCounters(array(
                'downloads_count' => 1
            ));

            if($this->isXAccelRedirect())
            {
                header('X-Accel-Redirect: '.$this->track->fileUrl);
            }
            else
            {
                readfile($this->track->filePath);
            }

            Yii::app()->end();
        }
    }

    /**
     * Страница самых загружаемых треков
     */
    public function actionTop()
    {
        $criteria = new CDbCriteria(array(
            'order' => 'downloads_count DESC',
        ));

        $pages = new CPagination;
        $pages->pageSize = 20;
        $pages->itemCount = 1000;
        $pages->applyLimit($criteria);

        $tracks = Track::model()->cache(60)->findAll($criteria);

        $this->render('top', array(
            'tracks' => $tracks,
            'pages' => $pages,
        ));
    }

    /**
     * Производит поиск трека по его ID вконтакте и делает
     * редирект на страницу поиска с названием этого трека
     *
     * @param $vk_id перевернутый ID трека вконтакте
     */
    public function actionVkId($vkid)
    {
        $vkid = explode('_', $vkid);
        $vkid = $vkid[1].'_'.$vkid[0];

        $api = new VkApi;
        $response = $api->execute('audio.getById', array(
            'audios' => $vkid,
        ));

        if($response !== NULL && isset($response->audio) && count($response->audio) > 0)
        {
            $audio = $response->audio[0];

            $this->redirect($this->createUrl('query/view', array(
                'text' => $this->normalizeQuery($audio->artist.' - '.$audio->title)
            )), TRUE, 301);
        }
        else
        {
            throw new CHttpException(404, 'Audiofile not found by id '.$vkid);
        }
    }

    /**
     * Коллбек, срабатывающий при удачной загрузке файла
     *
     * @param $curl дескриптор cURL
     */
    public function _callback_end($curl)
    {
        /**
         * При удачной загрузке сохраняем в базу
         */
        if($this->track->validate())
        {
            $this->track->save();
        }
        else
        {
            Yii::log('Cannot save track ('.$this->track->id.') ('.print_r($this->track->errors, 1).')', 'warning');
        }

        if( ! $this->streaming)
        {
            if($this->isXAccelRedirect())
            {
                header('X-Accel-Redirect: '.$this->track->fileUrl);
            }
            else
            {
                readfile($this->track->filePath);
            }
        }
    }

    /**
     * Коллбек, срабатывающий при чтении каждой части файла CURL'ом
     *
     * @param $curl дескриптор cURL
     * @param $string часть файла
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

            if($this->streaming)
            {
                ob_start();
            }
        }

        if($this->streaming)
        {
            /**
             * Посылаем часть файла браузеру в случае стриминга
             */
            echo $string;

            $this->_flush();
        }
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

    protected function isXAccelRedirect()
    {
        if(isset($_SERVER["SERVER_SOFTWARE"]) && strpos($_SERVER["SERVER_SOFTWARE"], 'Development Server') !== FALSE)
        {
            return FALSE;
        }

        return TRUE;
    }

    protected function redirectFilename($filename)
    {
        $this->redirect($this->createUrl('query/view', array(
            'text' => $this->normalizeQuery(str_replace('.mp3', '', $filename))
        )), TRUE, 301);
    }
}