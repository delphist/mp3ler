<?php

/**
 * Модель аккаунта вконтакте
 *
 * @property string $id
 * @property int $vk_id id аккаунта вконтакте
 * @property int $app_id id приложения вконтакте
 * @property int $is_alive статус аккаунта (1 - живой, 0 - мертвый)
 * @property int $is_captcha_request требует ли аккаунт каптчу
 * @property int $is_captcha_response статус решения каптчи
 * @property string $captcha_request_data данные о запросе каптчи
 *           представляют из себя json-обьект, при распаковке
 *           который содержит следующие поля:
 *            - string url: адрес изображения каптчи
 *            - string id: идентификатор каптчи вконтакте (captcha_sig)
 *            - int solve_id: id каптчи в сервисе распознавания каптчи
 * @property string $captcha_response_data решение каптчи
 * @property string $error_response json-обьект ответа последней ошибки api
 * @property int $captcha_count количество запросов каптчи с этого аккаунта
 * @property int $request_count количество запросов api этого аккаунта
 */
class VkAccount extends CActiveRecord
{
    public function tableName()
    {
        return 'vk_account';
    }

    public function rules()
    {
        return array(
            array('app_id, vk_id', 'unique')
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
        );
    }

    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id,true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Возвращает обьект с последней ошибкой API если она есть и
     * NULL в случае если ошибок не было
     *
     * @return null|stdClass
     */
    public function getVkError()
    {
        if($this->error_response)
        {
            if(is_string($this->error_response))
            {
                $this->error_response = json_decode($this->error_response);
            }

            return $this->error_response;
        }

        return NULL;
    }

    /**
     * Возвращает решение каптчи
     *
     * @return string
     */
    public function getCaptcha_Response()
    {
        return $this->captcha_response_data;
    }

    /**
     * Устанавливает решение каптчи
     *
     * @param $value
     */
    public function setCaptcha_Response($value)
    {
        $this->captcha_response_data = $value;

        return TRUE;
    }

    /**
     * Возвращает данные о запросе каптчи, если оно есть
     * и NULL в случае если запроса не было
     *
     * @return array|null
     */
    public function getCaptcha_Request()
    {
        if($this->captcha_request_data)
        {
            if( ! is_array($this->captcha_request_data))
            {
                $this->captcha_request_data = (array) json_decode($this->captcha_request_data);
            }

            return $this->captcha_request_data;
        }

        return NULL;
    }

    /**
     * Устанавливает данные о запросе каптчи
     *
     * @param $value
     */
    public function setCaptcha_Request($value)
    {
        $this->captcha_request_data = $value;

        return TRUE;
    }

    /**
     * Сериализует некоторые поля перед сохранением
     *
     * @return bool
     */
    public function beforeSave()
    {
        if(parent::beforeSave())
        {
            if($this->captcha_request_data)
            {
                if(is_array($this->captcha_request_data))
                {
                    $this->captcha_request_data = json_encode($this->captcha_request_data);
                }

                $this->is_captcha_request = TRUE;
            }
            else
            {
                $this->captcha_request_data = NULL;
                $this->is_captcha_request = FALSE;
            }

            if($this->captcha_response_data)
            {
                $this->is_captcha_response = TRUE;
            }
            else
            {
                $this->captcha_response_data = NULL;
                $this->is_captcha_response = FALSE;
            }

            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Генерирует URL приложения
     *
     * @return string
     */
    public function app_url()
    {
        return 'http://vk.com/app'.$this->app_id.'_'.$this->app_id;
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
