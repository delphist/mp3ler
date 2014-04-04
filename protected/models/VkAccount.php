<?php

/**
 * Модель аккаунта вконтакте
 *
 * @property string $id
 */
class VkAccount extends CActiveRecord
{
    const API_URL = 'http://api.vk.com/api.php';

    public function tableName()
    {
        return 'vk_account';
    }

    public function rules()
    {
        return array(

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

    public function execute_api($method, $params)
    {
        if ($params === FALSE)
        {
            $params = array();
        }

        $params = array_merge($params, array(
            'api_id' => $this->app_id,
            'v' => '3.0',
            'test_mode' => 1,
            'method' => $method,
        ));
        ksort($params);

        $signature = $this->vk_id;
        foreach($params as $k => $v)
        {
            $signature .= $k.'='.$v;
        }

        $params['sig'] = md5($signature);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, $this->app_url());
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.57 Safari/537.17');

        $data = curl_exec($ch);
        curl_close($ch);

        $result = simplexml_load_string($data);

        return $result;
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
