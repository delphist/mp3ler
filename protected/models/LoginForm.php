<?php

/**
 * Форма входа
 */
class LoginForm extends CFormModel
{
    /**
     * Время запоминания
     */
    public $ttl = 2592000; // месяц

    /**
     * @var Имя пользователя либо email
     */
    public $username;

    /**
     * @var Нешифрованный пароль
     */
    public $password;

    /**
     * @var bool Запоминать ли?
     */
    public $rememberMe = FALSE;

    /**
     * @var Класс identity
     */
    public $identity;

    public function rules()
    {
        return array(
            array('username, password', 'required'),
            array('rememberMe', 'boolean'),
            array('password', 'authenticate'),
        );
    }

    public function authenticate($attribute, $params)
    {
        $this->identity = new UserIdentity($this->username, $this->password);

        if( ! $this->identity->authenticate())
        {
            $this->addError('password', 'Incorrect password');
        }
    }

    public function login()
    {
        Yii::app()->user->login($this->identity, $this->rememberMe ? $this->ttl : NULL);
    }
}