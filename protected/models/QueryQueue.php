<?php

/**
 * Модель очереди для поисковых запросов
 */
class QueryQueue extends CActiveRecord
{
    protected $results_data;

    public function tableName()
    {
        return 'query_queue';
    }

    public function rules()
    {
        return array(
            array('query_id', 'required'),
            array('query_id', 'safe'),
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

        $criteria->compare('id', $this->id, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
