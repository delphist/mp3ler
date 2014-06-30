<?php

/**
 * Модель записи в чарте billboard.com
 *
 * @property $id
 * @property $title полное название трека
 * @property $position позиция в чарте
 */
class Billboard extends CActiveRecord
{
    public function tableName()
    {
        return 'billboard';
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created_at',
                'updateAttribute' => NULL,
            )
        );
    }

    public function rules()
    {
        return array(
            array('title', 'required'),
            array('title', 'unique'),
        );
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}