<?php

class UserIdentity extends CUserIdentity
{
    private $_id;

    const ERROR_USER_BLOCKED = 3;

    public function authenticate()
    {
        $user = User::model()->find('LOWER(email)=?', array(strtolower($this->username)));
        if ($user === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } elseif (User::model()->hashPassword($this->password, $user->salt) !== $user->password) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } elseif ($user->active != User::STATUS_ACTIVE) {
            $this->errorCode = self::ERROR_USER_BLOCKED;
        } else {
            $this->_id = $user->id;
            $this->errorCode = self::ERROR_NONE;
        }
        return $this->errorCode == self::ERROR_NONE;
    }

    public function getId()
    {
        return $this->_id;
    }
}