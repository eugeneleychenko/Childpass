<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'UserController'.
 */
class LoginForm extends CFormModel
{
    public $username;
    public $password;
    public $rememberMe;

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
            array('username', 'required', 'message' => 'Username cannot be blank.'),
            array('username', 'email'),
            array('password', 'required', 'message' => 'Password cannot be blank.'),
            // rememberMe needs to be a boolean
            array('rememberMe', 'boolean'),
            // password needs to be authenticated
            array('password', 'authenticate'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'rememberMe' => 'Remember Me',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->_identity = $this->returnIdentityObject();
            if (!$this->_identity->authenticate())
                $this->addError('password', 'Incorrect username/email or password.');
        }
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login()
    {
        if ($this->_identity === null) {
            $this->_identity = $this->returnIdentityObject();
            $this->_identity->authenticate();
        }

        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            $duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
            $user     = $this->returnCWebuserObject();
            $user->login($this->_identity, $duration);
            return true;
        } else
            $this->addError('password', 'Incorrect email or password.');
            return false;
    }

    public function returnIdentityObject()
    {
        $object = new UserIdentity($this->username, $this->password);
        return $object;
    }

    public function returnCWebuserObject()
    {
        return Yii::app()->user;
    }

}