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

    public function resetPassword()
    {
        /** @var User $user */
        $user = User::model()->findByAttributes(array('email' => $this->email));

        if (!$user) {
            $this->addError('email', 'User with such email does not exist!');
            return false;
        };

        $link = $user->generatePasswordResetLink();

        Yii::app()->common->sendEmail($user->email, 'Password Reset Link', 'user_password_reset', array('link' => $link));

        return true;
    }

}