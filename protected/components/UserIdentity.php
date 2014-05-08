<?php

class UserIdentity extends CUserIdentity
{
    private $_id;

    public function authenticate()
    {
        if(strpos($this->username, '@') !== FALSE)
        {
            $user = User::model()->findByAttributes(array('email' => $this->username));
        }
        else
        {
            $user = User::model()->findByAttributes(array('username' => $this->username));
        }

        if($user === NULL)
        {
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        }
        else
        {
            $check_user = new User(array(
                'password' => $this->password,
            ));

            if($user->password !== $check_user->password)
            {
                $this->errorCode=self::ERROR_PASSWORD_INVALID;
            }
            else
            {
                $this->_id = $user->id;
                $this->setState('id', $user->id);
                $this->errorCode = self::ERROR_NONE;
            }
        }

        return $this->errorCode === self::ERROR_NONE;
    }

    public function getId()
    {
        return $this->_id;
    }
}