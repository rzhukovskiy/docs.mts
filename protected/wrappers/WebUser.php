<?php

class WebUser extends CWebUser
{
    private $role = null;
    private $model = null;

    function getRole() {
        if ($user = $this->getModel()) {
            return $user->role;
        }
    }

    public function getModel()
    {
        if (!$this->isGuest && $this->model === null) {
            $this->model = User::model()->findByPk($this->id);
        }
        return $this->model;
    }
}