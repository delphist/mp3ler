<?php

/**
 * Форма изменения настроек пользователя
 */
class SettingsForm extends User
{
    /**
     * @var string Текущий пароль
     */
    protected $_currentPassword;

    /**
     * @var string Оригинальный хеш пароля
     */
    public $originalHashedPassword;

    /**
     * @var Хеш текущего пароля
     */
    public $currentHashedPassword;

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'password' => Yii::t('app', 'New password'),
            'currentPassword' => Yii::t('app', 'Current password'),
            'currentHashedPassword' => Yii::t('app', 'Current password'),
        ));
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('currentHashedPassword, originalHashedPassword, email, username, sitename', 'unsafe'),
            array('currentPassword', 'required'),
            array('currentHashedPassword', 'compare', 'compareAttribute' => 'originalHashedPassword'),
        ));
    }

    public function setCurrentPassword($value)
    {
        $this->_currentPassword = $value;
        $this->currentHashedPassword = $this->hashPassword($value);
    }

    public function getCurrentPassword()
    {
        return $this->_currentPassword;
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->originalHashedPassword = $this->hashed_password;
    }

    /**
     * Обнуляем значения введенных паролей, дабы не передавать их в форму
     */
    public function afterValidate()
    {
        parent::afterValidate();

        $this->_currentPassword = NULL;
        $this->_password = NULL;

        return TRUE;
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}