<?php

/**
 * Модель поискового запроса
 *
 * @property string $id
 * @property string $text текст запроса
 * @property string $results_count количество найденных результатов
 */
class Query extends CActiveRecord
{
    protected $results_data;

	public function tableName()
	{
		return 'query';
	}

	public function rules()
	{
		return array(
			array('text, results_count', 'required'),
			array('text', 'length', 'max' => 255, 'min' => 2),
			array('results_count', 'length', 'max' => 11),
			array('id, text', 'safe', 'on' => 'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'text' => 'Text',
			'results_count' => 'Results Count',
            'results_data' => 'Results',
		);
	}

    public function setResults($value)
    {
        if($value instanceof Audio)
        {
            $this->results_data = $value->results();
        }
        elseif($value instanceof Results)
        {
            $this->results_data = $value;
        }

        return TRUE;
    }

    public function getResults()
    {
        if($this->results_data == '')
        {
            $this->results_data = new Results;
        }

        return $this->results_data;
    }

    public function beforeValidate()
    {
        if(parent::beforeValidate())
        {
            $this->results_count = $this->results->count;

            if($this->results_count == 0)
            {
                return FALSE;
            }

            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function beforeSave()
    {
        if(parent::beforeSave())
        {
            $this->text = mb_strtolower($this->text);

            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function getTitle()
    {
        $title = explode(" ", $this->text);

        foreach($title as &$part)
        {
            $part = mb_strtoupper(mb_substr($part, 0, 1)).mb_strtolower(mb_substr($part, 1));
        }

        return implode($title, ' ');
    }

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('text',$this->text,true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
