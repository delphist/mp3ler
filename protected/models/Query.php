<?php

/**
 * Модель поискового запроса
 *
 * @property string $id
 * @property string $text текст запроса
 * @property string $results_count количество найденных результатов
 * @property string $hash md5-хеш, используемый для быстрого поиска
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
            $this->hash = $this->generateHash($this->text);

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

    public function hash($query)
    {
        $this->getDbCriteria()->mergeWith(array(
            'hash' => $this->generateHash($query)
        ));

        return $this;
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

    /**
     * Производит поиск запроса по его тексту
     *
     * @param $text текст запроса
     * @return Query|null
     */
    public function findByText($text)
    {
        $result = self::model()->findByAttributes(array(
            'hash' => $this->generateHash($text),
        ));

        if($result !== NULL)
        {
            return $result;
        }

        return self::model()->findByAttributes(array(
            'text' => $text,
        ));
    }

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

    protected function generateHash($string)
    {
        return md5($string);
    }
}
