<?php

/**
 * Модель трека
 */
class Track extends CActiveRecord
{
    protected $_filepointer;
    protected $_header_callback;
    protected $_body_callback;

    public function tableName()
    {
        return 'track';
    }

    public function rules()
    {
        return array();
    }

    public function attributeLabels()
    {
        return array();
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

    /**
     * Выполняет загрузку и сохранение файла с внешнего
     * URL-адреса в хранилище
     *
     * @param callable $header_callback
     * @param callable $body_callback
     * @return bool статус закачки файла
     */
    public function download(callable $header_callback = NULL, callable $body_callback = NULL)
    {
        $this->file = $this->storageFileName;

        try
        {
            $this->_header_callback = $header_callback;
            $this->_body_callback = $body_callback;

            $filedir = pathinfo($this->filePath, PATHINFO_DIRNAME);
            if( ! is_dir($filedir))
            {
                mkdir($filedir, 0755, TRUE);
            }
            $this->_filepointer = fopen($this->filePath, 'wb+');

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->data['url'],
                CURLOPT_FOLLOWLOCATION => TRUE,
                CURLOPT_AUTOREFERER => TRUE,
                CURLOPT_FAILONERROR => TRUE,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_HEADERFUNCTION => array($this, '_download_header_callback'),
                CURLOPT_WRITEFUNCTION => array($this, '_download_body_callback'),
            ));

            $result = curl_exec($curl);
            fclose($this->_filepointer);
            chmod($this->filePath, 0755);

            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if($code !== 200)
            {
                throw new Exception('Http code '.$code);
            }
        }
        catch(Exception $e)
        {
            if(is_file($this->filePath))
            {
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

            fwrite($this->_filepointer, $string);

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
        return $this->artist_title.' - '.$this->title.'.mp3';
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
}
