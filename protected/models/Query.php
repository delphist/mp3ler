<?php

/**
 * Модель поискового запроса
 *
 * @property string $id
 * @property string $text текст запроса
 * @property string $results_count количество найденных результатов
 * @property SearchResults $results модель найденных результатов
 */
class Query extends CActiveRecord
{
	public function tableName()
	{
		return 'query';
	}

	public function rules()
	{
		return array(
			array('text, results_count', 'required'),
			array('text', 'length', 'max'=>255),
			array('results_count', 'length', 'max'=>11),
			array('id, text', 'safe', 'on'=>'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'text' => 'Text',
			'results_count' => 'Results Count',
            'results' => 'Results',
		);
	}

    public function setResults($value)
    {
        $this->setAttribute('results', $value);
    }

    public function getResults()
    {
        if($this->getAttribute('results') == '')
        {
            $this->setAttribute('results', new SearchResults);
        }

        return $this->getAttribute('results');
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
