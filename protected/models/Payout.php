<?php

/**
 * Модель выплаты
 *
 * @property $id id выплаты
 */
class Payout extends CActiveRecord
{
    public function tableName()
    {
        return 'payout';
    }

    public function relations()
    {
        return array(
            'partner' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * Вычисляет размер выплаты для текущей модели
     */
    public function calculateAmount()
    {
        $this->amount = Yii::app()->transitionStatistics->show(array(
            'from' => $this->start_date,
            'to' => $this->end_date,
        ), array(
            'partner' => $this->partner,
            'countable' => TRUE,
            'return' => 'sum',
        ));

        return $this;
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}