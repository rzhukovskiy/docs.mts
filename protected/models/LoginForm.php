<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{

    public $username;
    public $password;
    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            // username and password are required
            array('username, password', 'required', 'message' => 'обязательно для заполнения'),
            array('password', 'authenticate'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'username' => 'Логин',
            'password' => 'Пароль',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params)
    {
        $this->_identity = new UserIdentity($this->username, $this->password);
        if (!$this->_identity->authenticate()) {
            if ($this->_identity->errorCode == UserIdentity::ERROR_USERNAME_INVALID)
                $this->addError('username', 'Неверный E-mail');
            if ($this->_identity->errorCode == UserIdentity::ERROR_USERNAME_INVALID || $this->_identity->errorCode == UserIdentity::ERROR_PASSWORD_INVALID || $this->_identity->errorCode == UserIdentity::ERROR_USER_BLOCKED)
                $this->addError('password', 'Неверное имя пользователя или пароль');
        }
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login()
    {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->username, $this->password);
            $this->_identity->authenticate();
        }

        if (Yii::app()->user->checkAccess(User::ADMIN_ROLE) || isset(Yii::app()->request->cookies['is_admin']->value)) {
            Yii::app()->user->login($this->_identity);
            return true;
        }

        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            //$duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
            Yii::app()->user->login($this->_identity);
            return true;
        }
        else
            return false;
    }

}
