<?php

/**
 * Модель кеша результатов аудио из вконтакте
 *
 * @property string $id
 */
class VkCache extends CActiveRecord
{
    public function tableName()
    {
        return 'vk_cache';
    }

    public function rules()
    {
        return array();
    }

    public function behaviors(){
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => NULL,
                'updateAttribute' => 'modified_at',
                'setUpdateOnCreate' => TRUE
            )
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

    public function findByQuery($query)
    {
        return $this->findByPk(md5($query));
    }

    public function setQuery($value)
    {
        $this->id = md5($value);

        return TRUE;
    }

    public function getResponse()
    {
        if(is_string($this->response_data))
        {
            try
            {
                $this->response_data = @unserialize($this->response_data);
            }
            catch(Exception $e)
            {
                return NULL;
            }

            if( ! is_array($this->response_data))
            {
                return NULL;
            }
        }

        return $this->response_data;
    }

    public function setResponse($value)
    {
        $this->response_data = $value;

        return TRUE;
    }

    public function beforeSave()
    {
        if(YII_DEBUG)
        {
            exit;
        }

        if(parent::beforeSave())
        {
            if( ! is_string($this->response_data))
            {
                $this->response_data = serialize($this->response_data);
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
