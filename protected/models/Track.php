<?php

/**
 * Модель трека
 *
 */
class Track extends CActiveRecord
{
    protected $url;

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

        $criteria->compare('id',$this->id,true);
        $criteria->compare('artist_title',$this->artist_title,true);
        $criteria->compare('title',$this->title,true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
