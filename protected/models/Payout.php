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

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created_at',
            )
        );
    }

    public function rules()
    {
        return array(
            array('startDateTimestamp, endDateTimestamp', 'safe'),
            array('user_id, end_date, amount', 'required'),
            array('amount', 'numerical', 'min' => 0.01),
            array('start_date, end_date', 'unsafe'),
            array('start_date, end_date', 'date', 'format' => 'yyyy-MM-dd hh:mm:ss')
        );
    }

    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    public function getPeriod()
    {
        return array(
            'from' => $this->startDateTimestamp,
            'to' => $this->endDateTimestamp,
        );
    }

    /**
     * Вычисляет размер выплаты для текущей модели
     */
    public function calculateAmount()
    {
        $this->amount = Yii::app()->transitionStatistics->show($this->period, array(
            'partner' => $this->user,
            'countable' => TRUE,
            'return' => 'sum',
        ));

        $this->transitions = Yii::app()->transitionStatistics->show($this->period, array(
            'partner' => $this->user,
            'countable' => TRUE,
            'return' => 'count',
        ));

        return $this;
    }

    public function getStartDateTimestamp()
    {
        if( ! $this->start_date)
        {
            return 0;
        }

        return CDateTimeParser::parse($this->start_date, 'yyyy-MM-dd hh:mm:ss');
    }

    public function getEndDateTimestamp()
    {
        return CDateTimeParser::parse($this->end_date, 'yyyy-MM-dd hh:mm:ss');
    }

    public function setEndDateTimestamp($value)
    {
        $this->end_date = date('Y-m-d H:i:s', $value);
    }

    public function setStartDateTimestamp($value)
    {
        if($value)
        {
            $this->start_date = date('Y-m-d H:i:s', $value);
        }
        else
        {
            $this->start_date = NULL;
        }
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}