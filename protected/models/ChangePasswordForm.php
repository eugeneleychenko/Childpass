<?php

class ChangePasswordForm extends CFormModel
{
    public $password;
    public $confirmPassword;


    public function rules()
    {
        return array(
            array('password, confirmPassword', 'required', 'message' => "Please, input password!"),
            array('password, confirmPassword', 'length', 'min'=> 6),
            array('password, confirmPassword', 'length', 'max'=> 64),
            array(
                'confirmPassword', 'compare',
                'compareAttribute'=>'password',
                'message'=>'Passwords are not equal!',
            ),
        );
    }

    public function attributeLabels()
    {
        return array(
            'password' => 'Password',
            'confirmPassword'=>'Confirm password',
        );
    }
}