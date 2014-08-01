<?php

/**
 * Модель записи артиста в чарте billboard.com
 *
 * @property $id
 * @property $title полное название артиста
 * @property $position позиция в чарте
 */
class BillboardArtist extends CActiveRecord
{
    public function tableName()
    {
        return 'billboard_artist';
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

    public function getImage()
    {
        return '/storage/billboard_artist/'.$this->id.'.jpg';
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}