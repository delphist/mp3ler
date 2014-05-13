<?php

/**
 * Форма изменения настроек пользователя
 */
class RegisterForm extends User
{
    /**
     * Проверка пароля
     *
     * @var string
     */
    protected $_currentPassword;

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'currentPassword' => Yii::t('app', 'Current password'),
            'currentHashedPassword' => Yii::t('app', 'Current password'),
        ));
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('currentPassword', 'required'),
            array('currentHashedPassword', 'compare', 'compareAttribute' => 'hashed_password')
        ));
    }

    public function setCurrentPassword($value)
    {
        $this->_currentPassword = $value;
    }
}