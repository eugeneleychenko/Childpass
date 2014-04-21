<?php

class LoginFormWidget extends CWidget
{
    public $model;

    public function init() {
        // this method is called by CController::beginWidget()
    }

    public function createUrl($input) {
        return Yii::app()->createUrl($input);
    }

    public function run() {
        // this method is called by CController::endWidget()

        if (!isset($this->model))
            $this->model = new LoginForm;

        $this->render('loginForm', array('model' => $this->model));
    }


}