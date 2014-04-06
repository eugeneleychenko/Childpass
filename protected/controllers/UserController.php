<?php

class UserController extends Controller
{

    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated users to access all actions
                  'users' => array('@'),
            ),
            array('allow',
                  'actions' => array('forgotPassword', 'passwordResetLinkSent', 'passwordReset',
                                     'passwordResetSuccess'),
                  'users'   => array('*')
            ),
            array('deny'),
        );
    }

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array();
    }

    public function actionForgotPassword()
    {

        if (!Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->homeUrl);
        }

        $this->layout = 'login';

        $model = new PasswordResetForm();

        // collect user input data
        if (isset($_POST['PasswordResetForm'])) {
            $model->attributes = $_POST['PasswordResetForm'];

            if ($model->resetPassword()) {
                $this->redirect(array('user/passwordResetLinkSent'));
            }
        }

        $this->render('forgotPassword', array('model' => $model));
    }

    public function actionPasswordResetLinkSent()
    {
        $this->layout = 'login';
        $this->render('passwordResetLinkSent', array());
    }

    public function actionPasswordReset()
    {
        $this->layout = 'login';

        $code = Yii::app()->request->getParam('code', false);

        if (!$code) {
            throw new CHttpException(400, 'Code parameter not provided!');
        }

        /** @var User $user */
        $user = User::model()->findByAttributes(array('password_reset_code' => $code));

        if (!$user) {
            throw new CHttpException(404, 'The link is incorrect or code is expired!');
        }

        $user->scenario = 'passwordReset';

        // collect user input data
        if (isset($_POST['User'])) {
            $user->attributes = $_POST['User'];

            $user->password            = $user->hashPassword($user->password);
            $user->password_reset_code = '';

            if ($user->save(true, array('password', 'password_reset_code'))) {
                $this->redirect(array('user/passwordResetSuccess'));
            }
        }

        $user->password = '';

        $this->render('passwordReset', array('model' => $user));
    }

    public function actionPasswordResetSuccess()
    {
        $this->layout = 'login';
        $this->render('passwordResetSuccess', array());
    }

}