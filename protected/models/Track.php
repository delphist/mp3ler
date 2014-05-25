<?php

/**
 * Модель трека
 *
 * @property $id id трека
 * @property $artist_title название исполнителя
 * @property $title название трека
 * @property $file имя файла
 * @property $external_type тип источника трека (сейчас доступен только vk)
 * @property $external_id идентификатор трека в источнике
 * @property $external_data данные о треке из источника
 * @property $content_length корректный размер файла (не обязательно фактический, который лежит на диске)
 */
class Track extends CActiveRecord
{
    protected $_filepointer;

    protected $_header_callback;
    protected $_body_callback;
    protected $_end_callback;

    public function tableName()
    {
        return 'track';
    }

    public function rules()
    {
        return array(
            array('artist_title, title, file', 'required'),
            array('artist_title, title, file', 'length', 'max' => 255),

            /**
             * Проверяем уникальность полей artist_title + title
             * @url http://stackoverflow.com/questions/9670992/yii-how-to-make-a-unique-rule-for-two-attributes
             */
            array('artist_title', 'unique', 'criteria' => array(
                    'condition'=>'`title`=:title',
                    'params' => array(
                        ':title' => $this->title
                    )
                )
            ),
        );
    }

    public function attributeLabels()
    {
        return array();
    }

    /**
     * Выполняет загрузку и сохранение файла с внешнего
     * URL-адреса в хранилище
     *
     * @param callable $header_callback
     * @param callable $body_callback
     * @param callable $end_callback
     * @return bool статус закачки файла
     */
    public function download(callable $header_callback = NULL, callable $body_callback = NULL, callable $end_callback = NULL)
    {
        $this->file = $this->storageFileName;

        $this->_filepointer = NULL;
        $this->_header_callback = $header_callback;
        $this->_body_callback = $body_callback;
        $this->_end_callback = $end_callback;

        try
        {
            $filedir = pathinfo($this->filePath, PATHINFO_DIRNAME);
            if( ! is_dir($filedir))
            {
                mkdir($filedir, 0755, TRUE);
            }

            $this->_filepointer = fopen($this->filePath, 'wb+');
            if($this->_filepointer === FALSE)
            {
                throw new Exception('Cannot open file ('.$this->id.') ('.$this->filePath.')');
            }

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->data['url'],
                CURLOPT_FOLLOWLOCATION => TRUE,
                CURLOPT_AUTOREFERER => TRUE,
                CURLOPT_FAILONERROR => TRUE,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HEADERFUNCTION => array($this, '_download_header_callback'),
                CURLOPT_WRITEFUNCTION => array($this, '_download_body_callback'),
            ));

            $result = curl_exec($curl);
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if($this->_filepointer != NULL)
            {
                if( ! fclose($this->_filepointer))
                {
                    throw new Exception('File is not closed ('.$this->id.') ('.$this->filePath.')');
                }
            }

            if($code !== 200)
            {
                throw new Exception('Http code '.$code);
            }

            if( ! file_exists($this->filePath))
            {
                throw new Exception('File is not saved ('.$this->id.') ('.$this->filePath.')');
            }

            $fileSize = filesize($this->filePath);
            if($fileSize != $this->content_length)
            {
                throw new Exception('Filesize is not equal content-length from source ('.$this->id.') (filesize '.$fileSize.' != content-length '.$this->content_length.')');
            }

            if( ! chmod($this->filePath, 0755))
            {
                throw new Exception('Cannot chmod file ('.$this->id.') ('.$this->filePath.')');
            }

            if(is_callable($this->_end_callback))
            {
                call_user_func($this->_end_callback, $curl);
            }
        }
        catch(Exception $e)
        {
            if(is_file($this->filePath))
            {
                Yii::log('Deleting wrong file ('.$this->filePath.') -> filesize ('.@filesize($this->filePath).')', 'warning');

                unlink($this->filePath);
            }

            $this->file = '';

            throw $e;

            return FALSE;
        }

        return TRUE;
    }

    protected function _download_header_callback($curl, $string)
    {
        if(is_callable($this->_header_callback))
        {
            call_user_func($this->_header_callback, $curl, $string);
        }

        return strlen($string);
    }

    protected function _download_body_callback($curl, $string)
    {
        /**
         * Стримим только валидный 200 заголовок. Обработка ошибок в другом месте
         */
        if((int) curl_getinfo($curl, CURLINFO_HTTP_CODE) === 200)
        {
            if(is_callable($this->_body_callback))
            {
                call_user_func($this->_body_callback, $curl, $string);
            }

            $this->content_length = curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

            if($this->_filepointer != NULL)
            {
                if(fwrite($this->_filepointer, $string) === FALSE)
                {
                    throw new Exception('Cannot write to file ('.$this->id.') ('.$this->filePath.')');
                }
            }

            return strlen($string);
        }
    }

    /**
     * Возвращает полный путь к файлу
     */
    public function getFilePath()
    {
        return Yii::app()->params['storage_path'].'/mp3/'.$this->file[0].'/'.$this->file[1].'/'.$this->file[2].'/'.$this->file;
    }

    /**
     * Возвращает полный URL на прямую скачку файла
     *
     * @return string
     */
    public function getFileUrl()
    {
        return '/storage/mp3/'.$this->file[0].'/'.$this->file[1].'/'.$this->file[2].'/'.$this->file;
    }

    /**
     * Проверяет, не нужно ли качать файл снова
     *
     * @return bool
     */
    public function getIsDownloaded()
    {
        if( ! $this->file)
        {
            return FALSE;
        }

        if( ! is_file($this->filePath))
        {
            Yii::log('File not exists, redownloading ('.$this->filePath.')', 'warning');

            return FALSE;
        }

        /**
         * Файлы менее 100 КБ скорее всего битые, перекачиваем заново
         */
        if(filesize($this->filePath) < 1024 * 100)
        {
            Yii::log('Filesize < 100 KB, redownloading ('.$this->filePath.')', 'warning');

            return FALSE;
        }

        return TRUE;
    }

    /**
     * Указывает, должен ли файл скачиваться
     *
     * @return bool
     */
    public function getDownloadable()
    {
        return isset($this->data['url']);
    }

    /**
     * Генерирует название файла, отображаемое пользователям
     * при скачивании
     *
     * @return string
     */
    public function getFilename()
    {
        $name = $this->artist_title.' - '.$this->title;
        $name = str_replace('/', ' ', $name);

        return $name.'.mp3';
    }

    /**
     * Возвращает полное название трека
     *
     * @return string
     */
    public function getFullTitle()
    {
        return $this->artist_title.' — '.$this->title;
    }

    /**
     * Возвращает полное название трека которое идет в поисковой запрос
     *
     * @return string
     */
    public function getSearchTitle()
    {
        return mb_strtolower($this->artist_title.' - '.$this->title);
    }

    /**
     * Генерирует название файла для хранения в файлохранилище
     *
     * @return string
     */
    public function getStorageFileName()
    {
        return sha1($this->artist_title.' - '.$this->title).'.mp3';
    }

    public function getData()
    {
        if($this->external_data == '')
        {
            return array();
        }
        elseif(is_string($this->external_data))
        {
            $this->external_data = (array) json_decode($this->external_data);
        }

        return $this->external_data;
    }

    public function setData(array $data)
    {
        $this->external_data = $data;

        $this->artist_title = $data['artist_title'];
        $this->title = $data['title'];
        $this->external_id = $data['id'];
        $this->external_type = $data['type'];
    }

    public function beforeSave()
    {
        if(parent::beforeSave())
        {
            if($this->external_data)
            {
                if(is_array($this->external_data))
                {
                    $this->external_data = json_encode($this->external_data);
                }
            }
            else
            {
                $this->external_data = NULL;
            }

            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function findByData($data)
    {
        if(isset($data['type']))
        {
            return self::model()->findByAttributes(array(
                'external_id' => $data['id'],
                'external_type' => $data['type'],
            ));
        }
        else
        {
            return self::model()->findByPk($data['id']);
        }
    }

    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('artist_title', $this->artist_title, true);
        $criteria->compare('title', $this->title, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
