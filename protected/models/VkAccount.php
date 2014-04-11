<?php

/**
 * Модель аккаунта вконтакте
 *
 * @property string $id
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

    public function getCaptcha_Response()
    {
        return $this->captcha_response_data;
    }

    public function setCaptcha_Response($value)
    {
        $this->captcha_response_data = $value;

        return TRUE;
    }

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

    public function setCaptcha_Request($value)
    {
        $this->captcha_request_data = $value;

        return TRUE;
    }

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

    public function app_url()
    {
        return 'http://vk.com/app'.$this->app_id.'_'.$this->app_id;
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
