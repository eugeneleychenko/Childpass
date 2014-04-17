<?php

/**
 * PasswordResetForm class.
 */
class PasswordResetForm extends CFormModel
{
    public $email;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            // username and password are required
            array('email', 'required', 'message' => "Please, provide email, which you've used during registration."),
            array('email', 'email'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'email' => 'E-mail',
        );
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

    public function resetPassword()
    {
        /** @var User $user */
        $user = User::model()->findByAttributes(array('email' => $this->email));

        if (!$user) {
            $this->addError('email', 'User with such email does not exists!');
            return false;
        };

        $link = $user->generatePasswordResetLink();

        Yii::app()->common->sendEmail($user->email, 'Password Reset Link', 'user_password_reset', array('link' => $link));

        return true;
    }

}