<?php

class WebUser extends CWebUser
{
    public function getId()
    {
        return $this->getState('id') ? $this->getState('id') : 0;
    }

    public function model()
    {
        return User::model()->findByPk($this->id);
    }
}