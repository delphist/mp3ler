<?php

/**
 * Форма регистрации пользователя, наследуется от модели пользователя
 * и добавляет проверку повторного ввода пароля
 */
class RegisterForm extends User
{
    /**
     * Подтверждение пароля
     *
     * @var string
     */
    public $verifyPassword;

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('password, verifyPassword', 'required'),
            array('verifyPassword', 'compare', 'compareAttribute' => 'password')
        ));
    }
}