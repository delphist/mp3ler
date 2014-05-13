<?php

/**
 * Модель пользователя
 *
 * @property $id id пользователя
 * @property $username имя пользователя, по которому идет вход
 * @property $email email-адрес
 * @property $hashed_password хешированный пароль, который хранится в БД
 * @property $sitename адрес сайта (для партнеров)
 * @property $group группа пользователя (partner, admin)
 */
class User extends CActiveRecord
{
    /**
     * Пароль в незашифрованном виде (в базе не хранится)
     * Используется для валидаций из форм регистрации, восстановления пароля, профиля
     *
     * @var string
     */
    protected $_password;

    public function tableName()
    {
        return 'user';
    }

    public function relations()
    {
        return array(
            'payouts' => array(self::HAS_MANY, 'Payout', 'user_id'),
            'payoutsSum' => array(self::STAT, 'Payout', 'user_id',
                'select' => 'SUM(amount)',
                'condition' => 'is_payed=1',
            ),
            'lastPayoutDate' => array(self::STAT, 'Payout', 'user_id',
                'select' => 'MAX(end_date)',
                'condition' => 'is_payed=1',
            )
        );
    }

    public function attributeLabels()
    {
        return array(
            'username' => Yii::t('app', 'Sitename'),
            'email' => Yii::t('app', 'E-mail'),
            'password' => Yii::t('app', 'Password'),
        );
    }

    public function rules()
    {
        return array(
            array('hashed_password', 'unsafe'),
            array('username, email, hashed_password, group', 'required'),
            array('username', 'length', 'max' => 20, 'min' => 3),
            array('password', 'length', 'max' => 150, 'min' => 5),
            array('email', 'email'),
            array('username, sitename', 'unique'),
            array('username, sitename', 'match', 'pattern' => '/^[A-Za-z0-9_\.\-]+$/u'),
            array('email', 'unique'),
        );
    }

    /**
     * Сеттер для пароля, при установке значения также автоматически
     * генерируется хеш
     *
     * @param $value string незашифрованный пароль
     */
    public function setPassword($value)
    {
        if($value)
        {
            $this->_password = $value;
            $this->hashed_password = $this->hashPassword($value);
        }
    }

    /**
     * Шифрует пароль
     *
     * @param $value исходный пароль
     * @return string зашифрованный пароль
     */
    public function hashPassword($value)
    {
        return sha1($value);
    }

    /**
     * Геттер для пароля
     *
     * @return string незашифрованный пароль
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * Показывает количество кликов партнера за определенное время
     *
     * @param $periodName
     */
    public function clicksCount($periodName)
    {
        $periodData = Yii::app()->transitionStatistics->parsePeriodName($periodName);
        unset($periodData['period']);

        return Yii::app()->transitionStatistics->show($periodData, array(
            'partner' => $this
        ));
    }

    /**
     * Возвращает количество выплаченных денег пользователю
     *
     * @return int
     */
    public function getTotalPayed()
    {
        return $this->payoutsSum;
    }

    /**
     * Возвращает количество денег которые нужно выплатить пользователю
     *
     * @return int
     */
    public function getTotalUnpayed()
    {
        return $this->createPayout()->calculateAmount()->amount;
    }

    public function getLastPayoutTimestamp()
    {
        return CDateTimeParser::parse($this->lastPayoutDate, 'yyyy-MM-dd hh:mm:ss');
    }

    /**
     * Создает новую выплату
     *
     * @return Payout
     */
    public function createPayout()
    {
        $model = new Payout;
        $model->startDateTimestamp = $this->lastPayoutTimestamp;
        $model->endDateTimestamp = time();
        $model->user_id = $this->id;
        $model->calculateAmount();

        return $model;
    }

    /**
     * Возвращает урл до сайта партнера
     */
    public function getSiteurl()
    {
        return 'http://'.$this->sitename.'/';
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}